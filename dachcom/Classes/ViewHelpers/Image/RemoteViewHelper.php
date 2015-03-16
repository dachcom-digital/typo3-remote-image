<?php
namespace DCD\DcdExtbaseEssentials\ViewHelpers\Image;

/**
 * ViewHelper to insert a remote image
 * = Examples =
 * <code>
 * {namespace dcd=DCD\DcdExtbaseEssentials\ViewHelpers}
 * <dcd:image.remote src="http://www.dachcom.com/fileadmin/templates/images/interface/logo.png" />
 * </code>

 */
class RemoteViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\ImageViewHelper {
    /**
     * Resizes a given image (if required) and renders the respective img tag
     * @see http://typo3.org/documentation/document-library/references/doc_core_tsref/4.2.0/view/1/5/#id4164427
     *
     * @param string  $src
     * @param string  $width              width of the image. This can be a numeric value representing the fixed width of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.
     * @param string  $height             height of the image. This can be a numeric value representing the fixed height of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.
     * @param integer $minWidth           minimum width of the image
     * @param integer $minHeight          minimum height of the image
     * @param integer $maxWidth           maximum width of the image
     * @param integer $maxHeight          maximum height of the image
     * @param boolean $treatIdAsReference given src argument is a sys_file_reference record
     *
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
     * @return string rendered tag.
     */
    public function render($src, $width = NULL, $height = NULL, $minWidth = NULL, $minHeight = NULL, $maxWidth = NULL, $maxHeight = NULL, $treatIdAsReference = FALSE) {
        return parent::render($this->getTargetFile($src), $width, $height, $minWidth, $minHeight, $maxWidth, $maxHeight, $treatIdAsReference);
    }

    /**
     * Return the path to the local representation of the remote file
     *
     * @param $src
     *
     * @return string
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
     */
    private function getTargetFile($src) {
        $fileName = basename($src);
        $extension = substr(strrchr($fileName, "."), 1);
        $hashName = sha1($src) . '.' . $extension;
        $targetFile = $this->getTempDir('ImageRemote') . $hashName;

        if (is_file($targetFile)) {
            return $targetFile;
        }

        $tempFile = rtrim(sys_get_temp_dir(), '/') . '/' . $hashName;

        if (!copy($src, $tempFile)) {
            throw new \TYPO3\CMS\Fluid\Core\ViewHelper\Exception('Could not copy remote image to temp from "' . htmlspecialchars($src) . '".');
        }

        if (!rename($tempFile, $targetFile)) {
            throw new \TYPO3\CMS\Fluid\Core\ViewHelper\Exception('Could not move remote image from temp to target "' . htmlspecialchars($src) . '".');
        }

        return $targetFile;
    }

    /**
     * Creates a temporary directory
     * (will not get deleted when clearing cache)
     *
     * @param $dir
     *
     * @return string
     */
    private function getTempDir($dir) {
        if (!is_dir(PATH_site . 'typo3temp')) {
            mkdir(PATH_site . 'typo3temp');
        }

        if (!is_dir(PATH_site . 'typo3temp/' . $dir)) {
            mkdir(PATH_site . 'typo3temp/' . $dir);
        }

        return PATH_site . 'typo3temp/' . rtrim($dir, '/') . '/';
    }
}
