typo3-remote-image
==================

This is a TYPO3 ViewHelper which allows you to insert a remote image with all the possibilities of the normal image ViewHelper.

Example
-------

```
{namespace dcd=DCD\DcdExtbaseEssentials\ViewHelpers}
<dcd:image.remote src="http://www.dachcom.com/fileadmin/templates/images/interface/logo.png" alt="" />
```


Things to consider
-----------------

This ViewHelper doesn't take care of the temporary files. You have to take care of them for yourself. They are located under "typo3temp/ImageRemote/"
