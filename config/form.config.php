<?php
namespace Admin42;

return array(
    'form_themes' => array(
        'admin42' => array(
            'layout_template'       => 'form/admin42/layout',
            'element_template_map'  => array(
                'text'      => 'form/admin42/elements/text',
                'email'      => 'form/admin42/elements/email',
                'color'      => 'form/admin42/elements/color',
                'datetime'      => 'form/admin42/elements/datetime',
                'datetime-local'      => 'form/admin42/elements/datetime-local',
                'password'  => 'form/admin42/elements/password',
                'hidden'    => 'form/admin42/elements/hidden',
                'checkbox'    => 'form/admin42/elements/checkbox',
                'multi_checkbox'    => 'form/admin42/elements/multi_checkbox',
                'submit'    => 'form/admin42/elements/submit',
                'select'    => 'form/admin42/elements/select',
                'textarea'  => 'form/admin42/elements/textarea',
                'wysiwyg'   => 'form/admin42/elements/wysiwyg',
            ),
        ),
    ),
);
