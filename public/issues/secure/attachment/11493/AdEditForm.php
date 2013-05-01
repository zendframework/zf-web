<?php

class AdEditForm extends Zend_Form 
{
    
    public function init()
    {
        
        $subForm = New Zend_Form_SubForm();
        $subForm->setLegend('adsFields');
        
        $validateNonZeroValue = new Zend_Validate_GreaterThan(0);
        $validateDate = new Zend_Validate_Date('Y-m-d');
        
        $elementUserId = new Zend_Form_Element_Hidden('user_id');
        $elementLanguageId = new Zend_Form_Element_Hidden('language_id');
        
        $elementActive = new Zend_Form_Element_Hidden('active');
            $elementActive->setValue(1);
        
        $elementAdsCategoryId = new Zend_Form_Element_Select('ads_category_id');
            $elementAdsCategoryId->setLabel('adsCategoryId');
            $elementAdsCategoryId->setMultiOptions(Ads_Categories::getSelectOptions());
            $elementAdsCategoryId->addValidator($validateNonZeroValue);
            $elementAdsCategoryId->setRequired(true);

        $elementAdsTypeId = new Zend_Form_Element_Select('ads_type_id');
            $elementAdsTypeId->setLabel('adsTypeId');
            $elementAdsTypeId->setMultiOptions(Ads_Types::getSelectOptions());
            $elementAdsTypeId->addValidator($validateNonZeroValue);
            $elementAdsTypeId->setRequired(true);

        $elementValidBefore = new Standart_Form_Element_Date('valid_until');
            $elementValidBefore->setLabel('adsValidUntil');
            $elementValidBefore->addValidator($validateDate);
            $elementValidBefore->setRequired(true);
            
        $elementTitle = new Zend_Form_Element_Text('title');
            $elementTitle->setLabel('adsTitle');
            $elementTitle->setAttrib('size', 75);
            $elementTitle->setRequired(true);
        $elementDescription = new Zend_Form_Element_Textarea('description');
            $elementDescription->setLabel('adsDescription');
            $elementDescription->setRequired(true);


        
        $captchaImage = new Zend_Captcha_Image();
        $captchaImage->setFont(Standart_Main::getDirs('fonts', 'arial.ttf'));
        $captchaImage->setFontSize(30);
        $captchaImage->setWordlen(6);
        $captchaImage->setImgDir(Standart_Main::getDirs('wwwStatic', array('images', 'captcha')));
        $captchaImage->setImgUrl(Zend_Registry::get('config')->host->static.'/images/captcha/');
        $captchaImage->setWidth(175);
        $captchaImage->setHeight(75);
        $captchaImage->setName('captcha');
        
        $elementCaptcha = new Zend_Form_Element_Captcha(
            'captcha',
            array(
                'captcha' => $captchaImage
            )
        );
        
        $elementDoSave = new Zend_Form_Element_Submit('doSave');
        
        $subForm->addElements(array(
            $elementUserId, $elementLanguageId,
            $elementActive,
            $elementAdsCategoryId, $elementAdsTypeId,
            $elementValidBefore,
            $elementTitle, $elementDescription,
            $elementCaptcha
        ));
        
        
        $this->addSubForm($subForm, 'ads');
        $this->addElement($elementDoSave);
        
    }
   
}