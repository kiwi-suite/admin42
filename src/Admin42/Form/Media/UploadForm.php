<?php
namespace Admin42\Form\Media;

use Admin42\Filter\File\RenameUpload;
use Zend\Form\Element\File;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\FileInput;
use Zend\InputFilter\InputFilter;

class UploadForm extends Form
{
    /**
     *
     */
    public function init()
    {
        $category = new Text("category");
        $this->add($category);

        $file = new File("file");
        $file->setAttribute("multiple", true);
        $this->add($file);

        $inputFilter = new InputFilter();

        $fileInput = new FileInput("file");
        $fileInput->setRequired(true);

        $renameUpload = new RenameUpload([
            'target'                => $this->getTargetPath(),
            'use_upload_name'       => true,
            'use_upload_extension'  => true,
        ]);
        $fileInput->getFilterChain()->attach($renameUpload);
        $inputFilter->add($fileInput);

        $this->setInputFilter($inputFilter);
    }

    /**
     * @return string
     */
    public function getTargetPath()
    {
        return 'data/media/' . implode(DIRECTORY_SEPARATOR, str_split(substr(md5(uniqid()), 0, 6), 2)) . '/';
    }
}
