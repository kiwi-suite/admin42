<?php
namespace Admin42\Media;

use Zend\EventManager\Event;

class MediaEvent extends Event
{
    const EVENT_EDIT_PRE = 'event_edit_pre';
    const EVENT_EDIT_POST = 'event_edit_post';
    const EVENT_ADD = 'event_add';
    const EVENT_DELETE = 'event_delete';
    const EVENT_CROP = 'event_crop';



}
