import SimpleParallax from "simple-parallax-js/vanilla";

var image = document.getElementsByClassName('img');
new simpleParallax(image);

//You can also choose to apply the parallax on multiple images:

var images = document.querySelectorAll('img1.png');
new simpleParallax(images);