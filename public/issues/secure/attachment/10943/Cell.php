<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @package    Zend_Pdf
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/** Zend_Pdf_Exception */
require_once 'Zend/Pdf/Exception.php';

/** Zend_Pdf_Resource_Font */
require_once 'Zend/Pdf/Resource/Font.php';

/** Zend_Pdf_Page*/
require_once 'Zend/Pdf/Page.php';

/** Zend_Pdf_Cmap */
require_once 'Zend/Pdf/Cmap.php';

/** Zend_Pdf_Color */
require_once 'Zend/Pdf/Color.php';

class Zend_Pdf_Cell {
	const ALIGN_LEFT=0;
	const ALIGN_RIGHT=1;
	const ALIGN_CENTER=2;
	const ALIGN_JUSTIFY=3;
	
	
	const POSITION_LEFT=0;
	const POSITION_RIGHT=1;
	const POSITION_CENTER_X=2;
	const POSITION_CENTER_Y=4;
	const POSITION_TOP=8;
	const POSITION_BOTTOM=16;
	
	/**
	 * Width of the cell
	 */
	private $_width;
	/**
	 * Height of the cell
	 */
	private $_height;
	/**
	 * Upper left X coordinate
	 */
	private $_x;
	/**
	 * Upper left Y coordinate
	 */
	private $_y;
	/**
	 * Current page for the cell to belong to.
	 */
	private $_page;
	
	/**
	 * How the cell should be positioned on the page
	 */
	private $_position;

	/**
	 * 3 diminsional array that stores the text in the cell.
	 * The first diminsion is for the line number.
	 * The second diminsion is for the section number.
	 * The third diminsion is the properties for that section.
	 * 
	 * The only properties (3rd diminsion) valid for a non-zero section (second diminsion)
	 * are text, font and encoding.
	 */
	private $_text;
	/**
	 * Keeps track of the current line number
	 */
	private $_lineNumber;
	/**
	 * Keeps track of the current section
	 */
	private $_section;
	
	/**
	 * Current font that is being used.
	 */
	private $_font;
	
	/**
	 * Current font's height
	 */
	private $_fontSize;

	/**
	 * When we want to auto-calculate the height, this is the
	 * height's current value.
	 */
	private $_autoHeight;
	
	/**
	 * When we want to aut-calculate the width, this is the
	 * width's current value.
	 */
	private $_autoWidth;
	
	/**
	 * Border around the cell.
	 * 
	 * Defaults to BORDER_NONE.
	 */
	private $_border;
	
	/**
	 * Creates a new cell.
	 *
	 * @param Zend_Pdf_Page $page Page for the cell to belong to.
	 * @param constant $position What type of position to have
	 * @param int $width Width of the cell.  Provide 0 for auto-width.
	 * @param int $height Height of the cell.  Provide 0 for auto-height.
	 */
	public function __construct($page,$position=Zend_Pdf_Cell::POSITION_LEFT,$width=0,$height=0) {
		$this->setPage($page);
		$this->_lineNumber=0;
		$this->_section=0;
		$this->_font=$page->getFont();
		$this->_fontSize=$page->getFontSize();
		
		$this->_initializeLine();
		$this->_initializeBorder();
		$this->setPosition($position);
		$this->setWidth($width);
		$this->setHeight($height);
	}
	
	private function _initializeBorder() {
		$this->_border['pattern']=Zend_Pdf_Page::LINE_DASHING_SOLID;
		$this->_border['size']=0;
		$this->_border['color']=new Zend_Pdf_Color_RGB(0,0,0);		
	}
	
	/**
	 * Wraps the section if needed.
	 * 
	 * This function explodes the section's text, and adds one word at a time until it goes over
	 * the boundry.  Once it goes over the boundry, it wraps the rest of the text up into its
	 * own text section and calls addText.  If the current section had been wrapped, then
	 * this function returns true, otherwise it returns false.
	 *
	 * @param array $section Section of text to wrap.
	 * @return boolean True if it wrapped text, false if it did not.
	 */
	private function _wordWrap($section) {
		if ($this->isAutoWidth()) {
			$maxWidth=$this->_page->getWidth();
		} else {
			$maxWidth=$this->_width;
		}
		$lineWidth=$this->_text[$this->_lineNumber]['width'];
		//if adding this section over flows borders
		if ($lineWidth+$section['width'] > $maxWidth) {
			//section of text is greater than our box's diminsions
			$splitSection=explode(' ',$section['text']);
			$maxTextSection=$this->_makeTextSection(array_shift($splitSection));
			while($maxTextSection['text']!=null && $lineWidth + $maxTextSection['width'] < $maxWidth) {
				$this->addText($maxTextSection['text'].' ');
				$lineWidth=$this->_text[$this->_lineNumber]['width'];
				$maxTextSection=$this->_makeTextSection(array_shift($splitSection));
			}
			$this->newLine();
			$restOfText=implode(' ',$splitSection);
			$this->addText($maxTextSection['text'].' '.$restOfText);
			return true;
		}
		return false;
	}
	
	/**
	 * Turns a string of text into a text section
	 *
	 * @param string $text String of text to turn into a section.
	 * @return array A text section array.
	 */
	private function _makeTextSection($text,$charEncoding='') {
		$section=array();
		$section['text']=$text;
		$section['encoding']=$charEncoding;
		$section['font']=$this->_font;
		$section['fontSize']=$this->_fontSize;
		$section['width']=$this->_getTextWidth($section);
		return $section;
	}

	/**
	 * Adds more text to the cell.  This will create a new section of text.  
	 * 
	 * The text is not written to the PDF until write() is called.
	 * 
	 * If this section is not the first section, then the alignment, x and y variables are ignored.
	 *
	 * @param string $text Text to add to the section
	 * @param mixed $alignment (Optional) How to align the text in the cell.  If no alignment is
	 *  specified, then it uses the previous line's alignment.  If this is the first line in the
	 *  cell, then it uses Zend_Pdf_Cell::ALIGN_LEFT as default.
	 * @param int $x (Optional) Offset of X from the relative position of this line in the cell.
	 * 	Defaults to 0
	 * @param string $charEncoding (Optional) Encoding of this particular section of text.
	 *  Defaults to current locale.
	 */
	public function addText($text, $alignment=null, $offset=0, $charEncoding='') {
		//Set the alignment
		if ($alignment!==null) {
			$this->_text[$this->_lineNumber]['alignment']=$alignment;
		} else {
			$this->_text[$this->_lineNumber]['alignment'];
			if ($this->_lineNumber==0) {
				if ($this->_text[$this->_lineNumber]['alignment']==null) {			
					//defaults to left alignment
					$this->_text[$this->_lineNumber]['alignment']=Zend_Pdf_Cell::ALIGN_LEFT;
				}
				//we have word wrapped on the first line, so don't do anything
			} else {
				//else defaults to previous line's alignment
				$this->_text[$this->_lineNumber]['alignment']=$this->_text[$this->_lineNumber-1]['alignment'];
			}
		}
		
		
		$section=$this->_makeTextSection($text,$charEncoding);
		
		if ($this->_wordWrap(&$section)) {
			return; //word wrap has already taken care of calling addText
		}
		
		$this->_text[$this->_lineNumber][$this->_section]['text']=$section['text'];
		$this->_text[$this->_lineNumber][$this->_section]['encoding']=$section['encoding'];
		$this->_text[$this->_lineNumber][$this->_section]['font']=$section['font'];
		$this->_text[$this->_lineNumber][$this->_section]['fontSize']=$section['fontSize'];
		$this->_text[$this->_lineNumber][$this->_section]['width']=$section['width'];
		
		//if we haven't set a width, do so and initialize it to the width of this piece of text,
		//otherwise just add the width to this piece of text.
		if (isset($this->_text[$this->_lineNumber]['width'])) {
			$this->_text[$this->_lineNumber]['width']+=$this->_text[$this->_lineNumber][$this->_section]['width'];
		} else {
			$this->_text[$this->_lineNumber]['width']=$this->_text[$this->_lineNumber][$this->_section]['width'];
		}
		$this->_text[$this->_lineNumber]['x']=$offset;
		
		//calculate the max width of the cell if we have an auto-size width cell
		if ($this->isAutoWidth()) {
			$this->_autoWidth = max($this->_text[$this->_lineNumber]['width'],$this->_autoWidth);
		}
		
		
		//calculate the max height of this line if we have an auto-size height cell
		if ($this->isAutoHeight()) {
			if (isset($this->_text[$this->_lineNumber]['height'])) {
				$this->_text[$this->_lineNumber]['height'] = max($this->_text[$this->_lineNumber]['height'], ($this->_font->getLineHeight()/$this->_font->getUnitsPerEm())*$this->_fontSize);
				$this->_autoHeight=max($this->_autoHeight,$this->_text[$this->_lineNumber]['height']);
			} else {
				$this->_text[$this->_lineNumber]['height']=($this->_font->getLineHeight()/$this->_font->getUnitsPerEm())*$this->_fontSize;
				$this->_autoHeight=($this->_font->getLineHeight()/$this->_font->getUnitsPerEm())*$this->_fontSize;
			}
		}
		$this->_section++;
	}
	
	/**
	 * Initialize a new line in the cell.
	 *
	 * @return void
	 */
	private function _initializeLine() {
		$this->_text[$this->_lineNumber]['x']=0;
		$this->_text[$this->_lineNumber]['width']=0;
		$this->_text[$this->_lineNumber]['height']=0;
		$this->_text[$this->_lineNumber]['alignment']=null;
	}
	
	/**
	 * Adds a new line to the cell.
	 */
	public function newLine() {
		$this->_section=0;
		$this->_lineNumber++;
		$this->_text[$this->_lineNumber]=array();
		$this->_text[$this->_lineNumber][0]['text']='';
		$this->_text[$this->_lineNumber][0]['encoding']='';
		$this->_text[$this->_lineNumber][0]['font']=$this->_font;
		$this->_text[$this->_lineNumber][0]['fontSize']=$this->_fontSize;
		$this->_text[$this->_lineNumber][0]['width']=0;

		
		$this->_initializeLine();
		
		$this->_text[$this->_lineNumber]['alignment']=$this->_text[$this->_lineNumber-1]['alignment'];
		//add the last cell's height to the auto height if we have an auto-height box.
		if ($this->isAutoHeight()) {
			$this->_autoHeight+=$this->_text[$this->_lineNumber-1]['height'];
		}
	}
	
	/**
	 * Returns the width of the cell, without the border
	 *
	 * @return int Width of cell, in pixels, without the border.
	 */
	public function getWidth() {
		return $this->_width;
	}
	
	/**
	 * Returns the height of the cell, without the border
	 *
	 * @return int Width of cell, in pixels, without the border.
	 */
	public function getHeight() {
		return $this->_height;
	}
	
	public function getPosition() {
		return $this->_position;
	}
			
	public function getPage() {
		return $this->_page;
	}
	
	/**
	 * Returns the type of border.
	 *
	 * Currently only a solid border is supported.
	 * 
	 * @return int Pattern of border
	 */
	public function getBorderPattern() {
		return $this->_border['pattern'];
	}
	
	/**
	 * Returns the size in pixels of the border around the cell.
	 * 
	 * @return int Size of the border, in pixels.
	 */
	public function getBorderSize() {
		return $this->_border['size'];
	}
	
	/**
	 * Returns the color of the border
	 *
	 * @return Zend_Pdf_Color Color of the border.
	 */
	public function getBorderColor() {
		return $this->_border['color'];
	}
	
	/**
	 * Sets the border around the cell.
	 * 
	 * A border may be placed around the cell.  If the border is greater than
	 * one pixel in width, then the border grows outwards away from the cell.
	 * 
	 * For Example:
	 * 
	 * If you create a cell with a width of 100 pixels, and you have a border of
	 * 10 pixels, then the total width of the cell is 120 pixels.
	 *
	 * @todo Implement more borders.
	 * @param int $pattern Type of border to draw.
	 * @param int $size Size of border, in pixels.
	 * @param Zend_Pdf_Color $color Color for the border.  Defaults to 
	 *  Zend_Pdf_Color_RGB(0,0,0)
	 */
	public function setBorder($size=1,$pattern=null,$color=null) {
		if ($pattern===null) {
			$this->_border['pattern']=Zend_Pdf_Page::LINE_DASHING_SOLID;
		} else {
			$this->_border['pattern']=$pattern;
		}
		
		$this->_border['size']=$size;
		if ($color===null) {
			$this->_border['color']=new Zend_Pdf_Color_RGB(0,0,0);
		}
	}
	

	/**
	 * Sets the location of where the cell's upper left corner should be placed.
	 * If the alignment is set, then x and y are offsets to the alignment.
	 *
	 * @param int $x X offset for the cell.
	 * @param int $y Y offset for the cell.
	 * @param mixed $alignment (Optional) How to align the cell with the X and Y as offsets.
	 * 	Defaults to none.
	 */
	public function setLocation($x, $y, $alignment=Zend_Pdf_Cell::ALIGN_LEFT) {
		$this->_position=$alignment;
		$this->_x=$x;
		$this->_y=$y;
		
	}

	/**
	 * Changes the current section's font to the one specified.
	 *
	 * @param Zend_Pdf_Font $font Font of the current section.
	 */
	public function setFont($font, $size) {
		$this->_font=$font;
		$this->_fontSize=$size;
	}
	
	/**
	 * Sets the current section's encoding to the encoding specified.
	 *
	 * @param string $encoding Character encoding of the text.
	 */
	public function setCharEncoding($encoding) {
		$this->_text[$this->_lineNumber][$this->_section]['encoding']=$encoding;
	}

	public function setPage($page) {
		$this->_page=$page;
	}
		
	/**
	 * Sets the maximum height of the cell, without the border.
	 *
	 * @todo Decide what to do if size of text exceeds cell height.
	 * @param int $height Maximum height of the cell, in pixels.
	 */
	public function setHeight($height) {
		$this->_height=$height;
		$this->_autoHeight=$height;
	}
	
	/**
	 * Sets the maximum width of the cell, without the border.
	 * 
	 * If text is added that exceeds the width of the cell, then the text
	 * will be wrapped at a space.
	 *
	 * @todo Decide what to do if one word exceeds border width.
	 * @param int $width Maximum width of the cell, in pixels.
	 */
	public function setWidth($width) {
		$this->_width=$width;
		$this->_autoWidth=$width;
	}
	
	public function setPosition($position) {
		$this->_position=$position;
	}
	
	/**
	 * Returns true if this cell is using auto width
	 *
	 * @return boolean If this cell is set to be auto-width
	 */
	public function isAutoWidth() {
		if ($this->_width==0) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Returns true if the cell is using auto height
	 *
	 * @return boolean If this cell is set to be auto height.
	 */
	public function isAutoHeight() {
		if ($this->_height==0) {
			return true;
		} else {
			return false;
		}
	}

	
	/**
	 * Draws the cell to the PDF page.
	 * 
	 * This function will parse the internal $_text field and draw the cell to the PDF page.
	 */
	public function write() {
		if (!($this->_page instanceof Zend_Pdf_Page)) {
			throw new Zend_Pdf_Exception("The PDF page that the cell is attempting to write to is not a valid page.");
		}
		if (!($this->_font instanceof Zend_Pdf_Resource_Font)) {
			throw new Zend_Pdf_Exception('No font has been set');
		}
		if ($this->isAutoHeight()) {
			$this->_height=$this->_autoHeight;
		}
		if ($this->isAutoWidth()) {
			$this->_width=$this->_autoWidth;
		}
		
		//positions of the cell's box

		//initalize the diminsions to defaults
		$top=$this->_y;
		$left=$this->_x;
		$right=$left+$this->getWidth();
		$bottom=$top+$this->getHeight();
		
		if ($this->_position & Zend_Pdf_Cell::POSITION_BOTTOM) {
			$top=$this->getHeight();
			$bottom=$top+$this->getHeight();
		}
		if ($this->_position & Zend_Pdf_Cell::POSITION_CENTER_X) {
			$left=$this->_page->getWidth()/2 - $this->getWidth()/2 + $this->_x;
			$right=$left+$this->getWidth();
		}
		if ($this->_position & Zend_Pdf_Cell::POSITION_CENTER_Y) {
			$top=$this->_page->getHeight()/2 + $this->getHeight()/2 - $this->_y;			
			$bottom=$top-$this->getHeight();		
		}
		if ($this->_position & Zend_Pdf_Cell::POSITION_TOP ) {
			$top=$this->_page->getHeight();
			$bottom=$top+$this->getHeight();
		}
		if ($this->_position & Zend_Pdf_Cell::POSITION_RIGHT) {
			$left=$this->_page->getWidth() - $this->getWidth();
			$right=$left+$this->getWidth();
		}
		$currentY=$top;
		//save the page's font so we can put it back after writing the cell
		$pageFont=$this->_page->getFont();
		$fontSize=$this->_page->getFontSize();
		
				
		//restore old size and font
		$this->_page->setFont($pageFont,$fontSize);
		//draw the border
		if ($this->_border['size']>0) {
			$style=new Zend_Pdf_Style();
			$style->setLineColor($this->getBorderColor());
			$style->setFillColor(new Zend_Pdf_Color_RGB(255,255,255));
			$style->setLineDashingPattern($this->getBorderPattern());
			$this->_page->setStyle($style);
			$this->_page->drawRectangle($right,$top,$left,$bottom);
			$style->setFillColor(new Zend_Pdf_Color_RGB(0,0,0));
			$this->_page->setStyle($style);
		}
		
		//draw every section of every page.
		for ($i=0;$i<count($this->_text);$i++) {
			$currentX=0;
			switch ($this->_text[$i]['alignment']) {
				case Zend_Pdf_Cell::ALIGN_RIGHT:
					$currentX=$right - $this->_text[$i]['width'];
					break;
				case Zend_Pdf_Cell::ALIGN_CENTER:
					$currentX=($right-$left)/2+$left-$this->_text[$i]['width']/2;
					break;
				case Zend_Pdf_Cell::ALIGN_JUSTIFY:
					//@todo
					break;
				default:
					$currentX=$left;
					break;
			}
			
			//add the offset
			$currentX+=$this->_text[$i]['x'];
			$currentY-=$this->_text[$i]['height'];
			//count() - 4 because of the 4 properties to this text.
			for ($j=0;$j<count($this->_text[$i])-4;$j++) {				
				$this->_page->setFont($this->_text[$i][$j]['font'],$this->_text[$i][$j]['fontSize']);
				$this->_page->drawText($this->_text[$i][$j]['text'],$currentX,$currentY,$this->_text[$i][$j]['encoding']);
				$currentX+=$this->_text[$i][$j]['width'];		
			}
		}
	}
	
	/**
	 * Returns the width of the text
	 *
	 * @param array $textSection A section of text that has the font, character encoding and text.
	 * @return integer Number of PDF units wide the text should be.
	 * @todo Make work for non ASCII characters.
	 */
	private function _getTextWidth($textSection) {
    	//make into a character array
    	$charArray=array();
    	for ($x=0;$x<strlen($textSection['text']);$x++) {
			$charArray[]=ord(substr($textSection['text'],$x,1));
		}
    	$charArray=$textSection['font']->cmap->glyphNumbersForCharacters($charArray);
    	//get the lengths
		$lengths=$textSection['font']->widthsForGlyphs($charArray);
		$fontGlyphWidth=array_sum($lengths);
		
       	return 	$fontGlyphWidth/$this->_font->getUnitsPerEm()*$this->_fontSize;
    }
}