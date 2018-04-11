// import external dependencies

import "jquery";

// Import everything from autoload
import "./autoload/**/*";

// uncomment what you need bootstrap or foundation
// import 'bootstrap';
// import Foundation from 'foundation-sites';
// If you want to pick and choose which modules to include, comment out the above and uncomment the line below
// import './util/foundation-explicit-pieces';

jQuery(document).ready(() => {
  $(document).foundation();
});
