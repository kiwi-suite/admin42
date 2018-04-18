module.exports = function(grunt) {
    grunt.initConfig({
        vendor_dir: 'node_modules',
        dist: 'dist',

        concurrent: {
            all: ['compile-vendor-js', 'compile-app-js', 'compile-tinymce', 'less:app']
        },

        concat: {
            options: {
                separator: ';\n'
            },
            vendor: {
                nonull: true,
                src: [
                    '<%= vendor_dir %>/jquery/dist/jquery.js',

                    '<%= vendor_dir %>/angular/angular.js',
                    '<%= vendor_dir %>/angular-animate/angular-animate.js',
                    '<%= vendor_dir %>/angular-sanitize/angular-sanitize.js',
                    '<%= vendor_dir %>/angular-ui-validate/dist/validate.js',
                    '<%= vendor_dir %>/ui-select/dist/select.js',

                    '<%= vendor_dir %>/angular-ui-tree/dist/angular-ui-tree.js',
                    '<%= vendor_dir %>/ngstorage/ngStorage.js',
                    '<%= vendor_dir %>/angularjs-toaster/toaster.js',
                    '<%= vendor_dir %>/angular-smart-table/dist/smart-table.js',

                    '<%= vendor_dir %>/angular-ui-bootstrap/dist/ui-bootstrap.js',
                    '<%= vendor_dir %>/angular-ui-bootstrap/dist/ui-bootstrap-tpls.js',

                    '<%= vendor_dir %>/magnific-popup/dist/jquery.magnific-popup.js',

                    '<%= vendor_dir %>/moment/min/moment-with-locales.js',
                    '<%= vendor_dir %>/moment-timezone/builds/moment-timezone-with-data.js'
                ],
                dest: '<%= dist %>/js/vendor.js'
            },
            app: {
                nonull: true,
                src: [
                    'javascripts/*.js',
                    'javascripts/service/*.js',
                    'javascripts/directive/*.js',
                    'javascripts/directive/form/*.js',
                    'javascripts/filter/*.js',
                    'javascripts/controller/*.js',
                    'javascripts/controller/link/*.js'
                ],
                dest: '<%= dist %>/js/admin42.js'
            }
        },

        uglify: {
            options: {
                mangle: false
            },
            app: {
                src: '<%= dist %>/js/admin42.js',
                dest: '<%= dist %>/js/admin42.min.js'
            },
            vendor: {
                src: '<%= dist %>/js/vendor.js',
                dest: '<%= dist %>/js/vendor.min.js'
            },
            tinymcelink42: {
                src: 'tinymce/link42/plugin.js',
                dest: 'tinymce/link42/plugin.min.js'
            }
        },

        less: {
            options: {
                compress: true,
                cleancss: true
            },
            app: {
                files: {
                    '<%= dist %>/css/admin42.min.css': [
                        '<%= vendor_dir %>/animate.css/animate.css',
                        '<%= vendor_dir %>/angularjs-toaster/toaster.css',
                        'less/main.less',
                        '<%= vendor_dir %>/ui-select/dist/select.css',
                        '<%= vendor_dir %>/magnific-popup/dist/magnific-popup.css'
                    ]
                }
            }
        },

        copy: {
            bootstrap: {
                files: [
                    {
                        expand: true,
                        flatten: true,
                        src: ['<%= vendor_dir %>/bootstrap/fonts/*'],
                        dest: '<%= dist %>/fonts/',
                        filter: 'isFile'
                    }
                ]
            },
            fontawesome: {
                files: [
                    {
                        expand: true,
                        flatten: true,
                        src: ['<%= vendor_dir %>/font-awesome/fonts/*'],
                        dest: '<%= dist %>/fonts/',
                        filter: 'isFile'
                    }
                ]
            },
            simpleline: {
                files: [
                    {
                        expand: true,
                        flatten: true,
                        src: ['<%= vendor_dir %>/simple-line-icons/fonts/*'],
                        dest: '<%= dist %>/fonts/',
                        filter: 'isFile'
                    }
                ]
            },
            tinymce: {
                files: [
                    {
                        expand: true,
                        cwd: '<%= vendor_dir %>/tinymce/',
                        src: '**',
                        dest: '<%= dist %>/tinymce/'
                    }
                ]
            },
            tinymcelink: {
                files: [
                    {
                        expand: true,
                        flatten: true,
                        src: ['tinymce/link42/*'],
                        dest: '<%= dist %>/tinymce/plugins/link42/',
                        filter: 'isFile'
                    }
                ]
            },
            flagicons: {
                files: [
                    {
                        expand: true,
                        flatten: true,
                        src: ['<%= vendor_dir %>/flag-icon-css/flags/4x3/**'],
                        dest: '<%= dist %>/flags/4x3/',
                        filter: 'isFile'
                    },
                    {
                        expand: true,
                        flatten: true,
                        src: ['<%= vendor_dir %>/flag-icon-css/flags/1x1/**'],
                        dest: '<%= dist %>/flags/1x1/',
                        filter: 'isFile'
                    }
                ]
            }
        },

        clean: {
            all: ['<%= dist %>/fonts/', '<%= dist %>/css/', '<%= dist %>/js/', '<%= dist %>/tinymce/', '<%= dist %>/flags/'],

            vendor: [
                '<%= dist %>/js/vendor.js'
            ],
            app: [
                '<%= dist %>/js/admin42.js'
            ],
            tinymce: [
                '<%= dist %>/tinymce/'
            ]
        },

        watch: {
            grunt: {
                files: ['Gruntfile.js'],
                tasks: ['default']

            },
            js: {
                files: ['javascripts/**/*.js'],
                tasks: ['compile-app-js']
            },
            less: {
                files: ['less/*.less', 'less/**/*.less'],
                tasks: ['compile-css']
            },
            tinymce: {
                files: ['tinymce/**/*.js'],
                tasks: ['compile-tinymce']
            }
        }
    });

    grunt.registerTask('default', ['clean:all', 'concurrent:all', 'copy']);
    grunt.registerTask('compile-vendor-js', ['concat:vendor', 'uglify:vendor']);
    grunt.registerTask('compile-app-js', ['concat:app', 'uglify:app']);
    grunt.registerTask('compile-css', ['less:app']);
    grunt.registerTask('compile-tinymce', ['clean:tinymce', 'uglify:tinymcelink42', 'copy:tinymce', 'copy:tinymcelink']);
    grunt.registerTask('clear', ['clean:all']);

    require('load-grunt-tasks')(grunt);
};
