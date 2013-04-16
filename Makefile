# framework.zend.com Makefile
#
# Primary purpose is for updating the site to add releases.
#
# Also provides a target for updating the home page to capture new blog posts.
#
# Release checklist:
# - [ ] Update config/autoload/module.downloads.global.php
#   - [X] Add VERSION and RELEASE_DATE mapping
#   - [ ] For ZF1 versions, update config/autoload/module.manual.global.php to map
#     minor -> maintenance version.
#   - [ ] For ZF2 versions, update config/autoload/module.manual.global.php to
#     ensure minor version is represented.
# - [ ] Update API version map for appropriate ZF version
#   - currently in Manual\Controller\PageController::apiAction
# - [ ] Need a target to copy/unzip manual/API docs to appropriate location on
#   server
# - [X] Update changelog for appropriate ZF version
# - [X] Need a target for updating homepage based on most recent blog posts in feed

VERSION ?= false
RELEASE_DATE ?= $(shell date +%F)
PHP ?= /usr/local/zend/bin/php

BIN = $(CURDIR)/bin

BLOG_FEED ?= $(CURDIR)/public/blog/feed-rss.xml
HOMEPAGE_PATH ?= $(CURDIR)/module/Application/view/application/index/index.phtml
HOMEPAGE_TEMPLATE ?= $(CURDIR)/data/homepage.phtml
SECURITY_CONFIG ?= $(CURDIR)/module/Security/config/module.config.php

.PHONY : all changelog checkVersion download-version homepage

all : download-version changelog homepage

homepage :
	@echo "Updating homepage feeds..."
	$(PHP) $(BIN)/update-homepage-feeds.php $(BLOG_FEED) $(SECURITY_CONFIG) $(HOMEPAGE_TEMPLATE) $(HOMEPAGE_PATH)
	@echo "[DONE] Updating homepage feeds."

changelog : checkVersion
	@echo "Updating changelog for version $(VERSION)..."
	$(PHP) public/index.php changelog fetch --version=$(VERSION)
	@echo "[DONE] Updating changelog."

download-version : checkVersion
	@echo "Adding version $(VERSION) to release downloads..."
	$(PHP) "$(BIN)/update-download-versions.php" $(VERSION) $(RELEASE_DATE) > module.downloads.global.php
ifeq ($$?,0)
	@echo "[FAILED] Failed to generate download versions"
	exit 1
endif
	-mv module.downloads.global.php config/autoload/module.downloads.global.php
	@echo "[DONE] Adding version to release downloads."

manual-version: checkVersion
	@echo "Adding version $(VERSION) manual mapping..."
	$(PHP) $(BIN)/update-manual-versions.php $(VERSION) > zf$(VERSION_MAJOR)-manual-versions.php
ifeq ($$?,0)
	@echo "[FAILED] Failed to generate manual version mapping"
	exit 1
endif
	-mv zf$(VERSION_MAJOR)-manual-versions.php config/autoload/zf$(VERSION_MAJOR)-manual-versions.php
	@echo "[DONE] Adding version manual mapping."

checkVersion :
ifeq ($(VERSION),false)
	@echo "Missing VERSION assignment on commandline"
	exit 1
endif
	$(eval VERSION_MAJOR := $(shell echo $(VERSION) | cut -f1 -d.))
	$(eval VERSION_MINOR := $(shell echo $(VERSION) | cut -f2 -d.))
