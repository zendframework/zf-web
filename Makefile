# framework.zend.com Makefile
#
# Primary purpose is for updating the site to add releases.
#
# Also provides a target for updating the home page to capture new blog posts.
#
# Configurable variables:
# - PHP - path to PHP executable
# - VERSION - version being released or added to site; required for all but
#   homepage target
# - RELEASE_DATE - release date in format Y-m-d, if other than current date
#
# Available targets:
# - homepage - update the homepage with latest feeds from blog and security
#   advisories
# - all - add a release version, update manual and apidoc mappings, and update
#   changelogs to include new version

VERSION ?= false
RELEASE_DATE ?= $(shell date +%F)
PHP ?= /usr/local/zend/bin/php

BIN = $(CURDIR)/bin

BLOG_FEED ?= $(CURDIR)/public/blog/feed-rss.xml
HOMEPAGE_PATH ?= $(CURDIR)/module/Application/view/application/index/index.phtml
HOMEPAGE_TEMPLATE ?= $(CURDIR)/data/homepage.phtml
SECURITY_CONFIG ?= $(CURDIR)/module/Security/config/module.config.php

.PHONY : all apidoc-version changelog check-version download-version homepage manual-version manual-latest-version

all : download-version manual-version manual-latest-version apidoc-version changelog homepage

homepage :
	@echo "Updating homepage feeds..."
	$(PHP) $(BIN)/update-homepage-feeds.php $(BLOG_FEED) $(SECURITY_CONFIG) $(HOMEPAGE_TEMPLATE) $(HOMEPAGE_PATH)
	@echo "[DONE] Updating homepage feeds."

changelog : check-version
	@echo "Updating changelog for version $(VERSION)..."
	$(PHP) public/index.php changelog fetch --version=$(VERSION)
	@echo "[DONE] Updating changelog."

download-version : check-version
	@echo "Adding version $(VERSION) to release downloads..."
	$(PHP) "$(BIN)/update-download-versions.php" $(VERSION) $(RELEASE_DATE) > module.downloads.global.php
ifeq ($$?,0)
	@echo "[FAILED] Failed to generate download versions"
	exit 1
endif
	-mv module.downloads.global.php config/autoload/module.downloads.global.php
	@echo "[DONE] Adding version to release downloads."

manual-version: check-version
	@echo "Adding version $(VERSION) to manual mapping..."
	$(PHP) $(BIN)/update-manual-versions.php $(VERSION) > zf$(VERSION_MAJOR)-manual-versions.php
ifeq ($$?,0)
	@echo "[FAILED] Failed to generate manual version mapping"
	exit 1
endif
	-mv zf$(VERSION_MAJOR)-manual-versions.php config/autoload/zf$(VERSION_MAJOR)-manual-versions.php
	@echo "[DONE] Adding manual version mapping."

manual-latest-version: check-version
ifeq ($(VERSION_MAJOR),2)
	@echo "Updating manual downloads version to $(VERSION)..."
	-cp module/Downloads/view/downloads/downloads/index.phtml.dist module/Downloads/view/downloads/downloads/index.phtml
	-sed -i s/{VERSION}/$(VERSION)/g module/Downloads/view/downloads/downloads/index.phtml
	@echo "[DONE] Updating manual downloads version"
endif

apidoc-version: check-version
	@echo "Adding version $(VERSION) to apidoc mapping..."
	$(PHP) $(BIN)/update-apidoc-versions.php $(VERSION) > zf-apidoc-versions.php
ifeq ($$?,0)
	@echo "[FAILED] Failed to generate apidoc version mapping"
	exit 1
endif
	-mv zf-apidoc-versions.php config/autoload/zf-apidoc-versions.php
	@echo "[DONE] Adding apidoc version mapping."

check-version :
ifeq ($(VERSION),false)
	@echo "Missing VERSION assignment on commandline"
	exit 1
endif
	$(eval VERSION_MAJOR := $(shell echo $(VERSION) | cut -f1 -d.))
	$(eval VERSION_MINOR := $(shell echo $(VERSION) | cut -f2 -d.))
