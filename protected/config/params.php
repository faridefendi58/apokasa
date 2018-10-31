<?php

// this contains the application parameters that can be maintained via GUI
return array(
    // this is displayed in the header section
    'title' => 'ApoKasa',
    // this is used in error pages
    'adminEmail' => 'webmaster@example.com',
    'emailTime' => 'd M Y, H:i:s',
    // number of posts displayed per page
    'postsPerPage' => 10,
    // maximum number of comments that can be displayed in recent comments portlet
    'recentCommentCount' => 10,
    // maximum number of tags that can be displayed in tag cloud portlet
    'tagCloudCount' => 20,
    // whether post comments need to be approved before published
    'commentNeedApproval' => true,
    // the copyright information displayed in the footer section
    'copyrightInfo' => 'Copyright &copy; 2009 by My Company.',
    'exceptionRbacAccess' => array('site', 'default', 'category', 'epdf', 'page', 'post'),
    'allowedFileExtension' => array("jpg", "jpeg", "gif", "png", "zip", "rar", "doc", "docx", "pdf", "txt", "xls", "xlsx", "ppt", "pptx"),
    'sizeLimit' => 100 * 1024 * 1024,
    'sessionTimeoutSeconds' => 3600 * 24
);
