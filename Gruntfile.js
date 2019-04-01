/* jshint node:true */
module.exports = function( grunt ) {
    'use strict';

    grunt.initConfig({

        //Metadata
        pkg: grunt.file.readJSON( 'package.json' ),

        // Generate POT files.
        makepot: {
            options: {
                type: 'wp-plugin',
                domainPath: 'languages',
                potHeaders: {
                    'report-msgid-bugs-to': 'https://github.com/madrasthemes/mas-static-content/issues',
                    'language-team': 'LANGUAGE <EMAIL@ADDRESS>'
                }
            },
            dist: {
                options: {
                    potFilename: '<%= pkg.name %>.pot',
                    exclude: [
                        'apigen/.*',
                        'tests/.*',
                        'tmp/.*'
                    ]
                }
            }
        },

        // Check textdomain errors.
        checktextdomain: {
            options:{
                text_domain: '<%= pkg.name %>',
                keywords: [
                    '__:1,2d',
                    '_e:1,2d',
                    '_x:1,2c,3d',
                    'esc_html__:1,2d',
                    'esc_html_e:1,2d',
                    'esc_html_x:1,2c,3d',
                    'esc_attr__:1,2d',
                    'esc_attr_e:1,2d',
                    'esc_attr_x:1,2c,3d',
                    '_ex:1,2c,3d',
                    '_n:1,2,4d',
                    '_nx:1,2,4c,5d',
                    '_n_noop:1,2,3d',
                    '_nx_noop:1,2,3c,4d'
                ]
            },
            files: {
                src:  [
                    '**/*.php',         // Include all files
                    '!apigen/**',       // Exclude apigen/
                    '!node_modules/**', // Exclude node_modules/
                    '!tests/**',        // Exclude tests/
                    '!vendor/**',       // Exclude vendor/
                    '!tmp/**'           // Exclude tmp/
                ],
                expand: true
            }
        },

        // Clean the directory.
        clean: {
            main: [
                '<%= pkg.name %>/',
                '<%= pkg.name %>*.zip'
            ]
        },

        // Creates deploy-able plugin
        copy: {
            main: {
                files: [ {
                    expand: true,
                    src: [
                        '**',
                        '!.*',
                        '!.*/**',
                        '.htaccess',
                        '!Gruntfile.js',
                        '!README.md',
                        '!package.json',
                        '!package-lock.json',
                        '!node_modules/**',
                        '!<%= pkg.name %>/**',
                        '!<%= pkg.name %>.zip',
                        '!none',
                        '!.DS_Store',
                        '!npm-debug.log'
                    ],
                    dest: '<%= pkg.name %>/'
                } ]
            }
        },

        compress: {
            build: {
                options: {
                    archive: '<%= pkg.name %>.zip',
                    mode: 'zip'
                },
                files: [ {
                    expand: true,
                    src: [
                        '**',
                        '!.*',
                        '!.*/**',
                        '.htaccess',
                        '!Gruntfile.js',
                        '!README.md',
                        '!package.json',
                        '!package-lock.json',
                        '!node_modules/**',
                        '!<%= pkg.name %>/**',
                        '!<%= pkg.name %>.zip',
                        '!none',
                        '!.DS_Store',
                        '!npm-debug.log'
                    ]
                } ]
            }
        }
    });

    // Load NPM tasks to be used here
    grunt.loadNpmTasks( 'grunt-wp-i18n' );
    grunt.loadNpmTasks( 'grunt-checktextdomain' );
    grunt.loadNpmTasks( 'grunt-contrib-clean' );
    grunt.loadNpmTasks( 'grunt-contrib-copy' );
    grunt.loadNpmTasks( 'grunt-contrib-compress' );

    // Register tasks
    grunt.registerTask( 'default', [
        'checktextdomain',
        'makepot'
    ]);

    grunt.registerTask( 'deploy', [
        'clean:main',
        'copy:main'
    ]);

    grunt.registerTask( 'build', [
        'compress:build'
    ]);
};
