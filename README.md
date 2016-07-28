# Beetroot Development Theme
This is a starter-theme for WordPress based on [Underscores](http://underscores.me/) that inclides three most popular Front-End frameworks: [Bootstrap](http://getbootstrap.com/), [ZURB Foundation](http://foundation.zurb.com/) and [Susy](http://susy.oddbird.net/). The purpose of this theme, is to act as a small and handy toolbox that contains the essentials needed to build any design, and its meant to be a starting point, not the final product.

## Requirements

This project uses [Gulp task runner](http://gulpjs.com/) that requires [Node.js](http://nodejs.org) v6.x.x  to be installed on your machine. 
If you haven't installed Gulp CLI on your machine yet, run:

```bash
$ npm install --global gulp-cli
```

## Quickstart

### 1. Clone the repository and install with npm

```bash
$ cd my-wordpress-folder/wp-content/themes/
$ git clone http://projects.beetroot.se:8081/borisenko/beetroot-theme.git
$ cd beetroot-theme
$ npm install
```

### 2. Setup your gulpfile.js:

#### 2.1 Live reload
Add your project's URL, so LiveReload can refresh browser as you are working on your code. (this also works great with local servers, such as XAMPP/MAMP, Vagrant, etc.):

```javascript
const syncUrl = 'projects.beetroot.se/clientname/projectname'
```


#### 2.2 (Optional) FTP/SFTP configuration. You can skip this if you are working locally

Host:

```javascript
const hostUrl = 'projects.beetroot.se'; 
```

Login:

```javascript
const uploadUser = 'your username'; 
```
Password:

```javascript
const uploadPass = 'your pasword'; 
```

Upload folder:

```javascript
const uploadFolder = '/wp-content/themes/beetroot-theme'; 
```



### 3. Setup framework

To enable one of the pre-installed frameworks, go to theme folder, then open framework-specific folder and simply copy its contents to root theme folder:

![Framework Setup](http://i.imgur.com/NZdaUCs.gif)

Then navigate to main scss file
`assets\src\scss\style.scss`
and uncomment import command for this framework:

![Framework SCSS](http://i.imgur.com/0QVlqdc.gif)

### 4. Run Gulp

While working on your project, run one of Gulp tasks that suits your workflow
* `gulp local` for local development
* `gulp ftp` or `gulp sftp` if you are working on remote server. Gulp will upload all changes and reload browser only after last file is successfully uploaded.

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
├───templates
└───vc_templates
```
### 2. Javascript
Write all your project's scripts to `assets\src\javascript\scripts.js`
You dont need to register each Javascript library in Wordpress anymore, simply put them to `assets\src\javascript\plugins` folder, and Gulp will combine them together with `scripts.js` in main `global.js` file, located in `assets\dist\javascript`. 

### 3. SCSS
All SCSS files are split into four main subfolders:

```
│       └───scss
│           ├───components
│           ├───framework
│           ├───layouts
│           └───template-parts
```

The `components` folder conains secondary styles, such as core Wordpress classes styling, forms, comments, etc. Put any small, reusable styles (buttons, etc) to `_parts.scss`.
All framework files located in `framework` folder. You can change Bootstrap settings in `bootstrap/_variables.scss`, and Foundation settings in `settings/_settings.scss`.
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
