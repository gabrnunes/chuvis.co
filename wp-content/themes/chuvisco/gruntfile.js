(function () {
  "use strict";
})();

module.exports = function (grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON("package.json"),

    concat: {
      options: {
        separator: "rnrn",
      },
      dist: {
        src: ["src/js/main.js"],
        dest: "dist/js/main.js",
      },
    },

    uglify: {
      options: {
        banner:
          '/*! <%= pkg.name %> <%= grunt.template.today("dd-mm-yyyy") %> */',
      },
      dist: {
        files: {
          "dist/js/main.min.js": ["<%= concat.dist.src %>"],
        },
      },
    },

    sass: {
      dist: {
        options: {
          style: "expanded",
        },
        files: {
          "dist/css/main.css": "src/scss/main.scss",
        },
      },
    },

    watch: {
      files: ["src/scss/*.scss"],
      tasks: ["newer:sass"],
    },
  });

  grunt.loadNpmTasks("grunt-newer");
  grunt.loadNpmTasks("grunt-contrib-concat");
  grunt.loadNpmTasks("grunt-contrib-uglify");
  grunt.loadNpmTasks("grunt-contrib-sass");
  grunt.loadNpmTasks("grunt-contrib-watch");
  grunt.registerTask("default", ["concat", "uglify", "sass"]);
};
