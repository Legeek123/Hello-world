import SimpleParallax from "simple-parallax-js/vanilla";

var image = document.getElementsByClassName('thumbnail');
new simpleParallax(image);

//You can also choose to apply the parallax on multiple images:

var images = document.querySelectorAll('img');
new simpleParallax(images);