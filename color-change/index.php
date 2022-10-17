<script src="https://code.jquery.com/jquery-3.6.0.js"></script>

<style>
* {
	transition: 0.5s;
}

body {
	background: #333;
	color: rgb(240,240,242);
	background: rgb(128,128,128);
	font-family: 'Calibri';
	padding: 0px;
	margin: 0px;
}

section {
	display: flex;
	height: 20vh;
	width: 100vw;
}

#color-container, #color-summary { margin: auto; }

#color-search {
	padding: 12px 20px;
	min-width: 350px;
	border-radius: 5px;
	outline: none;
	border: 1px solid rgb(100,100,100);
	font-size: 1.2rem;
	color: rgb(128,128,128);
}

table {
	border-collapse: collapse;
	font-size: 1.8rem;
	color: rgb(128,128,128);
}

table td {
	padding: 8px 15px;
	border-bottom: 1px solid rgb(128,128,128);
}

td:nth-child(1) { min-width: 80px; font-weight: bold; }
td:nth-child(2) { min-width: 280px; }


@keyframes slideInFromLeft {
	0% {
		transform: translateX(-100%);
		opacity: 0;
	}
	100% {
		transform: translateX(0);
		opacity: 1;
	}
}

body {
	animation: 1s ease-out 0s 1 slideInFromLeft;
}
</style>

<section id="color-bg">
	<div id="color-container">
		<span></span>
		<input type="text" id="color-search" />
	</div>
</section>

<section>
	<div id="color-summary">
		<table id="tbl-summary">
		<tr><td class="color-title">Hex </td><td id="val-hex"></td></tr>
		<tr><td class="color-title">HexA </td><td id="val-hexa"></td></tr>
		<tr><td class="color-title">RGB </td><td id="val-rgb"></td></tr>
		<tr><td class="color-title">RGBA </td><td id="val-rgba"></td></tr>
		<tr><td class="color-title">HSL </td><td id="val-hsl"></td></tr>
		<tr><td class="color-title">HSLA </td><td id="val-hsla"></td></tr>
		</table>
	</div>
</section>


<script>
function color(item) {
	// regex or colours
	RGB = new RegExp(/^rgb\((((((((1?[1-9]?\d)|10\d|(2[0-4]\d)|25[0-5]),\s?)){2}|((((1?[1-9]?\d)|10\d|(2[0-4]\d)|25[0-5])\s)){2})((1?[1-9]?\d)|10\d|(2[0-4]\d)|25[0-5]))|((((([1-9]?\d(\.\d+)?)|100|(\.\d+))%,\s?){2}|((([1-9]?\d(\.\d+)?)|100|(\.\d+))%\s){2})(([1-9]?\d(\.\d+)?)|100|(\.\d+))%))\)$/i);
	RGBA = new RegExp(/^rgba\((((((((1?[1-9]?\d)|10\d|(2[0-4]\d)|25[0-5]),\s?)){3})|(((([1-9]?\d(\.\d+)?)|100|(\.\d+))%,\s?){3}))|(((((1?[1-9]?\d)|10\d|(2[0-4]\d)|25[0-5])\s){3})|(((([1-9]?\d(\.\d+)?)|100|(\.\d+))%\s){3}))\/\s)((0?\.\d+)|[01]|(([1-9]?\d(\.\d+)?)|100|(\.\d+))%)\)$/i);
	Hex = new RegExp(/^#([\da-f]{3}){1,2}$/i);
	HexA = new RegExp(/^#([\da-f]{4}){1,2}$/i);
	HSL = new RegExp(/^hsl\(((((([12]?[1-9]?\d)|[12]0\d|(3[0-5]\d))(\.\d+)?)|(\.\d+))(deg)?|(0|0?\.\d+)turn|(([0-6](\.\d+)?)|(\.\d+))rad)((,\s?(([1-9]?\d(\.\d+)?)|100|(\.\d+))%){2}|(\s(([1-9]?\d(\.\d+)?)|100|(\.\d+))%){2})\)$/i);
	HSLA = new RegExp(/^hsla\(((((([12]?[1-9]?\d)|[12]0\d|(3[0-5]\d))(\.\d+)?)|(\.\d+))(deg)?|(0|0?\.\d+)turn|(([0-6](\.\d+)?)|(\.\d+))rad)(((,\s?(([1-9]?\d(\.\d+)?)|100|(\.\d+))%){2},\s?)|((\s(([1-9]?\d(\.\d+)?)|100|(\.\d+))%){2}\s\/\s))((0?\.\d+)|[01]|(([1-9]?\d(\.\d+)?)|100|(\.\d+))%)\)$/i);

	if (item != "") {
		if (item.match(RGB)) {
			convertToHexA = RGBToHexA(item);
		} else if (item.match(RGBA)) {
			convertToHexA = RGBAToHexA(item);
		} else if (item.match(Hex)) {
			convertToHexA = HexToHexA(item);
		} else if (item.match(HexA)) {
			convertToHexA = item;
		} else if (item.match(HSL)) {
			convertToHexA = HSLToHexA(item);
		} else if (item.match(HSLA)) {
			convertToHexA = HSLAToHexA(item);
		} else {
			convertToHexA = "FAILED";
		}
	}

	return convertToHexA;
}

function RGBToHexA(rgb) {
	let sep = rgb.indexOf(",") > -1 ? "," : " ";
	rgb = rgb.substr(4).split(")")[0].split(sep);

	let r = (+rgb[0]).toString(16),
	    g = (+rgb[1]).toString(16),
	    b = (+rgb[2]).toString(16);

	if (r.length == 1)
		r = "0" + r;
	if (g.length == 1)
		g = "0" + g;
	if (b.length == 1)
		b = "0" + b;

	return "#" + r + g + b + 'ff';
}

function RGBAToHexA(rgba) {
	let sep = rgba.indexOf(",") > -1 ? "," : " "; 
	rgba = rgba.substr(5).split(")")[0].split(sep);

	// Strip the slash if using space-separated syntax
	if (rgba.indexOf("/") > -1)
		rgba.splice(3,1);

	for (let R in rgba) {
		let r = rgba[R];
 		if (r.indexOf("%") > -1) {
			let p = r.substr(0,r.length - 1) / 100;

			if (R < 3) {
				rgba[R] = Math.round(p * 255);
			} else {
				rgba[R] = p;
			}
		}
	}

	let r = (+rgba[0]).toString(16),
	g = (+rgba[1]).toString(16),
	b = (+rgba[2]).toString(16),
	a = Math.round(+rgba[3] * 255).toString(16);

	if (r.length == 1)
		r = "0" + r;
	if (g.length == 1)
		g = "0" + g;
	if (b.length == 1)
		b = "0" + b;
	if (a.length == 1)
		a = "0" + a;

	return "#" + r + g + b + a;
}

function HexToHexA(hex) {
	if (hex.length === 4) {
		hex = hex.split("").map((xitem)=>{
			if(xitem == "#"){return xitem}
				return xitem + xitem;
		}).join("");

		hex = hex + 'ff';
	} else {
		hex = hex + 'ff';
	}

	return hex;
}

function HSLToHexA(hsl) {
	let sep = hsl.indexOf(",") > -1 ? "," : " ";
	hsl = hsl.substr(4).split(")")[0].split(sep);

	let h = hsl[0],
	    s = hsl[1].substr(0,hsl[1].length - 1) / 100,
	    l = hsl[2].substr(0,hsl[2].length - 1) / 100;

	if (h.indexOf("deg") > -1)
		h = h.substr(0,h.length - 3);
	else if (h.indexOf("rad") > -1)
		h = Math.round(h.substr(0,h.length - 3) * (180 / Math.PI));
	else if (h.indexOf("turn") > -1)
		h = Math.round(h.substr(0,h.length - 4) * 360);

	if (h >= 360)
		h %= 360;
    
	let c = (1 - Math.abs(2 * l - 1)) * s,
	    x = c * (1 - Math.abs((h / 60) % 2 - 1)),
	    m = l - c/2,
	    r = 0,
	    g = 0,
	    b = 0;

	if (0 <= h && h < 60) {
		r = c; g = x; b = 0;
	} else if (60 <= h && h < 120) {
		r = x; g = c; b = 0;
	} else if (120 <= h && h < 180) {
		r = 0; g = c; b = x;
	} else if (180 <= h && h < 240) {
		r = 0; g = x; b = c;
	} else if (240 <= h && h < 300) {
		r = x; g = 0; b = c;
	} else if (300 <= h && h < 360) {
		r = c; g = 0; b = x;
	}

	r = Math.round((r + m) * 255).toString(16);
	g = Math.round((g + m) * 255).toString(16);
	b = Math.round((b + m) * 255).toString(16);

	if (r.length == 1)
		r = "0" + r;
	if (g.length == 1)
		g = "0" + g;
	if (b.length == 1)
		b = "0" + b;

	return "#" + r + g + b + 'ff';
}

function HSLAToHexA(hsla) {
	let sep = hsla.indexOf(",") > -1 ? "," : " ";
	hsla = hsla.substr(5).split(")")[0].split(sep);

	if (hsla.indexOf("/") > -1)
		hsla.splice(3,1);

	let h = hsla[0],
	    s = hsla[1].substr(0,hsla[1].length - 1) / 100,
	    l = hsla[2].substr(0,hsla[2].length - 1) / 100,
	    a = hsla[3];

	if (h.indexOf("deg") > -1)
		h = h.substr(0,h.length - 3);
	else if (h.indexOf("rad") > -1)
		h = Math.round(h.substr(0,h.length - 3) * (180 / Math.PI));
	else if (h.indexOf("turn") > -1)
		h = Math.round(h.substr(0,h.length - 4) * 360);
	if (h >= 360)
		h %= 360;

	if (a.indexOf("%") > -1)
		a = a.substr(0,a.length - 1) / 100;
  
	let c = (1 - Math.abs(2 * l - 1)) * s,
	    x = c * (1 - Math.abs((h / 60) % 2 - 1)),
	    m = l - c/2,
	    r = 0,
	    g = 0,
	    b = 0;
    
	if (0 <= h && h < 60) {
		r = c; g = x; b = 0;
	} else if (60 <= h && h < 120) {
		r = x; g = c; b = 0;
	} else if (120 <= h && h < 180) {
		r = 0; g = c; b = x;
	} else if (180 <= h && h < 240) {
		r = 0; g = x; b = c;
	} else if (240 <= h && h < 300) {
		r = x; g = 0; b = c;
	} else if (300 <= h && h < 360) {
		r = c; g = 0; b = x;
	}

	r = Math.round((r + m) * 255).toString(16);
	g = Math.round((g + m) * 255).toString(16);
	b = Math.round((b + m) * 255).toString(16);
	a = Math.round(a * 255).toString(16);

	if (r.length == 1)
		r = "0" + r;
	if (g.length == 1)
		g = "0" + g;
	if (b.length == 1)
		b = "0" + b;
	if (a.length == 1)
		a = "0" + a;

	return "#" + r + g + b + a;
}

function HexToHSL(H) {
	let r = 0, g = 0, b = 0;
	if (H.length == 4) {
		r = "0x" + H[1] + H[1];
		g = "0x" + H[2] + H[2];
		b = "0x" + H[3] + H[3];
	} else if (H.length == 7) {
		r = "0x" + H[1] + H[2];
		g = "0x" + H[3] + H[4];
		b = "0x" + H[5] + H[6];
	}

	r /= 255;
	g /= 255;
	b /= 255;

	let cmin = Math.min(r,g,b),
	    cmax = Math.max(r,g,b),
	    delta = cmax - cmin,
	    h = 0,
	    s = 0,
	    l = 0;

	if (delta == 0)
		h = 0;
	else if (cmax == r)
		h = ((g - b) / delta) % 6;
	else if (cmax == g)
		h = (b - r) / delta + 2;
	else
	h = (r - g) / delta + 4;

	h = Math.round(h * 60);

	if (h < 0)
		h += 360;

	l = (cmax + cmin) / 2;
	s = delta == 0 ? 0 : delta / (1 - Math.abs(2 * l - 1));
	s = +(s * 100).toFixed(1);
	l = +(l * 100).toFixed(1);

	return "hsl(" + h + "," + s + "%," + l + "%)";
}

function HexToRGB(h) {
	let r = 0, g = 0, b = 0;

	if (h.length == 4) {
		r = "0x" + h[1] + h[1];
		g = "0x" + h[2] + h[2];
		b = "0x" + h[3] + h[3];
	} else if (h.length == 7) {
		r = "0x" + h[1] + h[2];
		g = "0x" + h[3] + h[4];
		b = "0x" + h[5] + h[6];
	}
  
	return "rgb("+ +r + "," + +g + "," + +b + ")";
}

function HexAToRGBA(h) {
	let r = 0, g = 0, b = 0, a = 1;

	if (h.length == 5) {
		r = "0x" + h[1] + h[1];
		g = "0x" + h[2] + h[2];
		b = "0x" + h[3] + h[3];
		a = "0x" + h[4] + h[4];
	} else if (h.length == 9) {
		r = "0x" + h[1] + h[2];
		g = "0x" + h[3] + h[4];
		b = "0x" + h[5] + h[6];
		a = "0x" + h[7] + h[8];
	}
	a = +(a / 255).toFixed(3);

	return "rgba(" + +r + "," + +g + "," + +b + "," + a + ")";
}

function HexAToHSLA(H) {
	let r = 0, g = 0, b = 0, a = 1;

	if (H.length == 5) {
		r = "0x" + H[1] + H[1];
      		g = "0x" + H[2] + H[2];
      		b = "0x" + H[3] + H[3];
      		a = "0x" + H[4] + H[4];
	} else if (H.length == 9) {
		r = "0x" + H[1] + H[2];
		g = "0x" + H[3] + H[4];
		b = "0x" + H[5] + H[6];
		a = "0x" + H[7] + H[8];
	}

	r /= 255;
	g /= 255;
	b /= 255;

	let cmin = Math.min(r,g,b),
	    cmax = Math.max(r,g,b),
	    delta = cmax - cmin,
	    h = 0,
	    s = 0,
	    l = 0;

	if (delta == 0)
		h = 0;
	else if (cmax == r)
		h = ((g - b) / delta) % 6;
	else if (cmax == g)
		h = (b - r) / delta + 2;
	else
		h = (r - g) / delta + 4;

	h = Math.round(h * 60);

	if (h < 0)
		h += 360;

	l = (cmax + cmin) / 2;
	s = delta == 0 ? 0 : delta / (1 - Math.abs(2 * l - 1));
	s = +(s * 100).toFixed(1);
	l = +(l * 100).toFixed(1);

	a = (a / 255).toFixed(3);

	return "hsla("+ h + "," + s + "%," + l + "%," + a + ")";
}


function HexAToAll(HexA) {
	let arrColor = [];

	Hex = HexA.slice(0, -2);
	HexA = HexA;
	RGB = HexToRGB(Hex);
	RGBA = HexAToRGBA(HexA);
	HSL = HexToHSL(Hex);
	HSLA = HexAToHSLA(HexA);

	arrColor.push(Hex);
	arrColor.push(HexA);
	arrColor.push(RGB);
	arrColor.push(RGBA);
	arrColor.push(HSL);
	arrColor.push(HSLA);

	return arrColor;
}

$("#color-search").change(function(){
	colorx = $("#color-search").val();
	arrColor = HexAToAll(color(colorx));
	console.log(arrColor);
	$("#color-search").css('background', arrColor[2]);
	$("#tbl-summary").css('color', arrColor[2]);
	$("td").css('border-bottom', "1px solid " + arrColor[2]);

	$("#val-hex").text(arrColor[0]);
	$("#val-hexa").text(arrColor[1]);
	$("#val-rgb").text(arrColor[2]);
	$("#val-rgba").text(arrColor[3]);
	$("#val-hsl").text(arrColor[4]);
	$("#val-hsla").text(arrColor[5]);
});
</script>




</script>

<!--
Good colours:
Burnt Orange
#CC5500
#CD5700

Port:
#692545
#643A48

Deep Red: #850101

Palatinate: #682860

120,20,0

-->

