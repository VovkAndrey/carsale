# Beetroot Development Theme
This is a starter-theme for WordPress based on [Underscores](http://underscores.me/) that includes three most popular Front-End frameworks: [Bootstrap](http://getbootstrap.com/), [ZURB Foundation](http://foundation.zurb.com/) and [Susy](http://susy.oddbird.net/). The purpose of this theme, is to act as a small and handy toolbox that contains the essentials needed to build any design, and its meant to be a starting point, not the final product.

All project dependensies can be added with `bower`, and `wiredep` plugin automatically parses their paths and includes during compilation (Please note that for SCSS you still need to use @import tag to include scss file, but thanks to wiredep you can use shortcuts like `../bootstrap` for example)

## Requirements

This project uses [Gulp task runner](http://gulpjs.com/) that requires [Node.js](http://nodejs.org) v6.x.x  to be installed on your machine. 
If you haven't installed Gulp CLI on your machine yet, run:

```bash
$ npm install --global gulp-cli
```

## Quickstart

### 1. Clone the repository and prepare your theme

```bash
$ cd my-wordpress-folder/wp-content/themes/
$ git clone http://git.beetroot.se/vromanenko/beetroot-starter.git
$ rename beetroot-theme folder (better with underscores or together like in 'twentyseventeen')
$ cd your_theme_name
```

### 1.2 Setup framework

To enable one of the pre-installed frameworks, go to theme folder, then open framework-specific folder and simply copy its contents to root theme folder:

![Framework Setup](http://i.imgur.com/dqVv2T9.gif)

### 1.3 Rename files and strings according to the project name
```bash
$ in the beginning of functions.php find Text domain definition and replace 'theme_text_domain' to the text domain according to your theme name
$ find & replace in the whole theme 'beetroot_' and 'beetroot' with the name of your theme
$ check top comment section in /style.css and assets/src/scss/style.scss for the correct information about the theme
$ add screenshot.png of your future theme appearance (1200px wide by 900px)
```

### 2. Install with npm and bower:
```bash
$ npm install
$ bower install
```

### 3. Setup your gulpfile.js:

#### 3.1 Live reload
Add your local server URL, so LiveReload can refresh browser as you are working on your code :

```javascript
var URL = 'localhost/myproject'
```

### 4. Run Gulp

While working on your project, run "watch" task from the NPM: `npm run watch`
When project is done, run `npm run production` to minify CSS, JS and remove unnecessary sourcemaps

### 5. Git commit like 'Theme setup'

## Overview
### 1. Folder structure

```
beetroot-theme/
├───assets
│   ├───dist
│   │   ├───css
│   │   ├───fonts
│   │   ├───images
│   │   └───javascript
│   └───src
│       ├───images
│       ├───javascript
│       │   └───plugins
│       └───scss
│           ├───components
│           ├───framework
│           ├───layouts
│           └───template-parts
├───languages
├───lib
├───template-parts
├───page-templates
└───vc_templates
```
### 2. Javascript
Write all your project's scripts to `assets\src\javascript\scripts.js`. Separate modules can be placed inside `assets\src\javascript\plugins` folder (Note that those scripts will be added before scripts.js during concatenation)
All developer scripts from `assets\src\javascript` folder will be concatenated with `bower` dependensies and plugins.
Sometimes one of the bower parts doesnt automatically includes (this happens if plugin author didnt added correct tags in bower config). To fix this, add relative path to required JS file to PATHS array in gulpfile:
```javascript
// Add custom JS
PATHS.js.push(
    'assets/src/javascript/plugins/*.js',
    'assets/src/javascript/scripts.js',
    'assets/components/matchHeight/dist/jquery.matchHeight.js' // <= Example
);
```

### 3. SCSS
All SCSS files are split into four main subfolders:

```
│       └───scss
│           ├───components
│           ├───layouts
│           └───template-parts
```

The `components` folder conains secondary styles, such as core Wordpress classes styling, forms, comments, etc. Put any small, reusable styles (buttons, etc) to `_parts.scss`.
The `layouts` folder is a place for all your page/template specific styles (header/footer, single, 404, etc). Try to avoid writing styles directly in `styles.scss`, its main purpose is to connect all your files for compilation via `@import`.
The `template-parts` folder should contain styles for reusable Wordpress template parts.

### 4. Images
Gulp will compress all images from `assets\src\images\` and put them to `assets\dist\images`. If you use FTP/SFTP, only compressed images will be uploaded to server.
### 5. PHP and `\lib` folder

The `lib` folder contains php files, connected to `functions.php`:

* `cleanup.php` - contains functions for cleaning up default Wordpress styles, scripts, etc.
* `enqueue-scripts.php` - serves for registering your styles and scripts in Wordpress
* `framework.php` - contains pre-configured menu walker, comment form and pagination with classes, specific to the framework you are using. Each framework has its own `framework.php` file.
* `jetpack.php` - handles compatibility issues if Jetpack plugin is enabled.
* `menu-areas.php` - serves for registering menu areas.
* `template-tags.php` - contains pre-configured custom template tags.
* `theme-support.php` - serves for registering theme support for languages, menus, post-thumbnails, post-formats etc. Most of them are already enabled.
* `vc_shortcodes.php` - serves for registering VisualComposer modules.
* `widget-areas.php` - serves for registering menu areas.

### 5. Workflow features

#### 5.1 Helper Classes

The theme contains javascript library, that detects user browser, OS and displays their names as classes for `<body>` tag. This allows you to easily debug all device or browser-specific ussies.
Also, if sidebar is active, `<body>` will have class `has_sidebar`.

#### 5.2 Working with breakpoints

The theme have default mixin that gives you fast and easy way to interact with responsive breakpoints<br>

```scss
@mixin breakpoint($point) {
    // Extra small devices (320px +)
    @if $point == xs {
         @media only screen and (min-width : 320px){
            @content;
        }
    }
    // Small devices (480px +)
    @else if $point == sm {
         @media only screen and (min-width : 480px){
            @content;
        }
    }
    // Tablets (768px +)
    @else if $point == tb {
         @media only screen and (min-width : 768px){
            @content;
        }
    }
    // Medium Devices, Notebooks (992px +)
    @else if $point == md {
         @media only screen and (min-width : 992px){
            @content;
        }
    }
    // Large Devices, Wide Screens
    @else if $point == desktop {
         @media only screen and (min-width : 1200px){
            @content;
        }
    }
}
```

You can add breakpoints directly into css block by adding 

```scss
@include breakpoint ( /* breakpoint name */ ) { /* breakpoint-specific styles */ }
```

Example:

![Breakpoint include](http://i.imgur.com/7uIM947.gif)

Compiled result:
```css
.myfancyblock {
  width: 100%;
}
@media only screen and (min-width : 992px){
  .myfancyblock {
    width: 50%;
  }
}
```

If you are PhpStorm user, you can make this process even faster, by adding a [live template](https://www.jetbrains.com/help/phpstorm/10.0/live-templates.html). For this, go to `File > Settings > Editor > Live Templates`, add new template, then enter an abbreviation (for example, word `breakpoint`) and description. In "Template text" field add the following code:

```
@include breakpoint ( $END$ ) {}
```

Then press "Change" at the bottom and select "CSS".

Example:

![Live template](http://i.imgur.com/cgdz3SS.gif)

Now you are able to call breakpoint mixin by simply typing first few letters of its abbreviation and pressing enter:

![Live template example](http://i.imgur.com/HonCC3Y.gif)

## Test your project
* [Dummy content generator wpfill.me](http://www.wpfill.me/)
* [Set of data to test all core Wordress features (post/content formats, multilevel menus, etc)](http://wptest.io/)
* https://codex.wordpress.org/Theme_Development#Theme_Testing_Process
* https://codex.wordpress.org/Theme_Unit_Test

## Pull Requests

Pull requests are highly appreciated. Please follow these guidelines:

1. Solve a problem. Features are great, but even better is cleaning-up and fixing issues in the code that you discover
2. Make sure that your code is bug-free and does not introduce new bugs
3. Create a [pull request](https://help.github.com/articles/creating-a-pull-request)
