<?php
use Bitrix\Main\Page\Asset;
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/apls_css/main.css", true);
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/apls_css/delivery.css", true);
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/apls_css/product-labels.css", true);
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/apls_css/smart-filter.css", true);
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/apls_css/product-element.css", true);
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/apls_css/page-navigator.css", true);
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/apls_css/promotions.css", true);
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/apls_css/vacancies.css", true);
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/apls_css/buttons.css", true);
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/apls_css/features-products.css", true);
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/apls_css/personal-cabinet.css", true);
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/apls_css/section-menu.css", true);
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/apls_css/orders.css", true);
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/apls_css/credit.css", true);
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/apls_css/loyalty.css", true);
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/apls_css/404.css", true);
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/apls_css/custom.css", true);

// версия для печати должна быть последней
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/apls_css/print-page.css", true);