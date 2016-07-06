<?php
namespace Admin42\Crud;

use Zend\EventManager\Event;

class CrudEvent extends Event
{
    const EVENT_EDIT_PRE = 'event_edit_pre';
    const EVENT_EDIT_POST = 'event_edit_post';
    const EVENT_ADD = 'event_add';
    const EVENT_DELETE = 'event_delete';
}
