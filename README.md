# DBMS-LAMP

Background: The researchers of an organization collected a large size of data from many devices. They want to merge the data into several relations. However, the data is lack of relevance though they are in similar format. For instance, a same object can exist in several data set and different data sets could have common and different attributes.

This is a Browser/Server structure DBMS based on LAMP. Briefly, it serves for merging data stored in Excel spreadsheets and generating Excel spreadsheets of part of data in database. In detail, it provides four main functions -- uploading, downloading, variable manager, and file manager.

1. Upload: User would upload Excel spreadsheets and the system will construct relations by merging the data. A module called PHPexcel is used for manipulating Excel spreadsheets.
2. Download: User can download data based on attribute group (a group of attributes created in variable manager).
3. Variable manager: User would need different part of data for different purposes. The same attribute group would be reused frequenty. Therefore, variable manager allow users to create attribute groups by combining part of variables in a relation or some common variables in several relations. An open-source plugin called jsTree is used to realize the tree-like structure.
4. File manager: User could distribute files into proper directory when uploading. And they could also do some changes after uploading like renaming, moving, and deleting.


Languages: HTML, JavaScript, PHP

Library: jQuery, PHPExcel

Framework: Bootstrap

Plugin: jsTree
