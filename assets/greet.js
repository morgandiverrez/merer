
// assets/greet.js

export default function(name) {
    return `Yo yo ${name} - welcome to Encore!`;
};

 // loads the jquery package from node_modules
 import $ from 'jquery';

 // import the function from greet.js (the .js extension is optional)
 // ./ (or ../) means to look for a local file
 import greet from './greet';

 $(document).ready(function() {
     $('body').prepend('<h1>'+greet('jill')+'</h1>');
 });