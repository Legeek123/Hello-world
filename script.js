import SimpleParallax from "simple-parallax-js/vanilla";

var image = document.getElementsByClassName('parallax');
new simpleParallax(image);

//You can also choose to apply the parallax on multiple images:

var images = document.getElementById('para');
new simpleParallax(images);