# sdats-draw-a-map
Online map drawing interface used for the perceptual dialectology task in the SDATS survey (Swiss German Dialects Across Time and Space)

# Functionalities

This custom made drawing tool is based on Leaflet FreeDraw https://github.com/Wildhoney/Leaflet.FreeDraw (https://freedraw.herokuapp.com/ ), written in Javascript. We invite you to test the SDATS interface (https://map.dialektatlas.ch) using codes ranging from 'SC10' to 'SC99'.

The participant logs in using a 4-digit UID assigned to them. They see a digital map prepared specifically for the SDATS data collection. Zooming is not possible and panning the map is only possible using the keyboard arrows.

The participant draws shapes on the map by mouse (or touchpad), clicking and dragging lines. When the mouse button is released, the shape drawn closes automatically, drawing a straight line between the mouse pointer and the starting point of the shape. Overlapping shapes are possible, but starting to draw a shape in another polygon is not. Every new shape is automatically saved into a geojson file on a server - one file corresponds to one participant. Deleting shapes is possible by clicking in their middle. Adjusting a shape is possible by moving the vertices and adding new vertices (elbows) in between existing ones.

Importantly, the shapes are automatically simplified after they are finished, which makes them less complex than originally drawn by the speaker. It is possible to change the smoothing factor: https://github.com/Wildhoney/Leaflet.FreeDraw#setting-modes

# Scientific article
A scientific articla based on the results of the mapping survey conducted with the tool is in review at the journal 'Digital Scholarship in the Humanities'

# Acknowledgements
The code hosted here was written by Daniel Wanitsch (ibros.ch) and was used for data collection for the Swiss German Dialects Across Time and Space (SDATS) project at the University of Bern, supported by the Swiss National Science Foundation grant no. 181090
The theoretical design of the program took place in 2020 was also supported by the SDATS project members Adrian Leemann, Péter Jeszenszky, Carina Steiner, Melanie Studerus and Jan Messerli.

----
For further information, please visit www.sdats.ch
