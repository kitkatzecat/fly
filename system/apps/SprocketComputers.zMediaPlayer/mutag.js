!(function (e) {
	function r(n) {
		if (t[n]) return t[n].exports;
		var i = (t[n] = { i: n, l: !1, exports: {} });
		return e[n].call(i.exports, i, i.exports, r), (i.l = !0), i.exports;
	}
	var t = {};
	(r.m = e),
		(r.c = t),
		(r.d = function (e, t, n) {
			r.o(e, t) || Object.defineProperty(e, t, { configurable: !1, enumerable: !0, get: n });
		}),
		(r.n = function (e) {
			var t =
				e && e.__esModule
					? function () {
						return e.default;
					}
					: function () {
						return e;
					};
			return r.d(t, "a", t), t;
		}),
		(r.o = function (e, r) {
			return Object.prototype.hasOwnProperty.call(e, r);
		}),
		(r.p = ""),
		r((r.s = 0));
})([
	function (e, r, t) {
		"use strict";
		var n = t(1),
			i = (function (e) {
				return e && e.__esModule ? e : { default: e };
			})(n),
			o = Object.create(null);
		(o.version = "2.0.7"), (o.fetch = i.default), (window.mutag = o);
	},
	function (e, r, t) {
		"use strict";
		function n(e) {
			var r = new FileReader();
			return (
				r.readAsArrayBuffer(e),
				new Promise(function (t, n) {
					r.onload = function () {
						(e = new Uint8Array(r.result)), i(e, t);
					};
				})
			);
		}
		function i(e, r) {
			var t = e.slice(0, 10),
				n = (127 & t[9]) | ((127 & t[8]) << 7) | ((127 & t[7]) << 14) | ((127 & t[6]) << 21);
			if (!("ID3" !== (0, u.getStr)(0, 3, t) || 3 !== t[3])) {
				(e = e.slice(10)), 64 == (64 & t[5]) && ((n -= 10), (e = e.slice(10))), r(o(e, n));
			} else {
				Player.file = {
					bname: Fly.file.string.bname(Player.url)
				};
				Player.loadReady();
			}
		}
		function o(e, r) {
			var t,
				n,
				i,
				o,
				f,
				l = 0,
				s = 0,
				d = Object.create(null);
			for (d.PRIV = Object.create(null); s < r && ((t = e.subarray(s, s + 10)), (f = e[10]), (n = (0, u.getStr)(0, 4, t)), 0 != (l = 268435456 * t[4] + 65536 * t[5] + 256 * t[6] + t[7]));)
				if (((s += 10 + l), (o = e.subarray(s - l, s)), "APIC" === n)) {
					var g = (0, u.getImgIndex)(o);
					(i = o.subarray(g.i, l)), (i = new Blob([i], { type: "image/" + g.format })), (d[n] = i);
				} else "PRIV" === n ? (0, c.parsePRIV)(o, d.PRIV) : (d[n] = a(o, f));
			return !Object.keys(d.PRIV).length && delete d.PRIV, d;
		}
		function a(e, r) {
			var t = 0,
				n = void 0,
				i = "";
			if ((0 === r || 87 === r) && 1 !== e[0])
				for (; t < e.length;)
					e[t] < 127
						? ((i += String.fromCharCode(e[t])), t++)
						: ((n = e.slice(t, t + 2)), 1 == n.length ? ((i += new TextDecoder("iso-8859-1").decode(n.buffer)), t++) : ((n = new Uint16Array(n.buffer)), (i += new TextDecoder("gbk").decode(n.buffer)), (t += 2)));
			return (1 === r || (1 === e[0] && 0 === r)) && (-1 !== e.lastIndexOf(254) && ((e = e.slice(e.lastIndexOf(254) + 1)), (e = new Uint16Array(e.buffer))), (i = (0, u.getStr)(0, e.length, e))), (i = (0, u.filterStr)(i));
		}
		Object.defineProperty(r, "__esModule", { value: !0 }), (r.default = n);
		var c = t(2),
			u = t(3);
	},
	function (e, r, t) {
		"use strict";
		function n(e, r) {
			switch (e.length) {
				case 14:
					r.PeakValue = o(e);
					break;
				case 17:
					r.AverageLevel = o(e);
					break;
				case 28:
					r["WM/UniqueFileIdentifier"] = a(e.slice(24));
					break;
				case 31:
					r["WM/WMContentID"] = c(e.slice(14));
					break;
				case 34:
					r["WM/WMCollectionID"] = c(e.slice(17));
					break;
				case 39:
					(e = e.slice(-16)), (e = i(e)), (r["WM/MediaClassPrimaryID"] = e);
					break;
				case 41:
					(e = e.slice(-16)), (e = i(e)), (r["WM/MediaClassSecondaryID"] = e);
					break;
				case 44:
					r["WM/Provider"] = u(e.slice(11));
			}
			return r;
		}
		function i(e) {
			var r = "",
				t = void 0;
			return (
				(e = Array.prototype.slice.call(e)),
				(e = e.map(function (e) {
					return e.toString(16);
				})),
				(t = e.slice(0, 4)),
				t.reverse(),
				(r += t.join("") + "-"),
				(t = e.slice(4, 6)),
				t.reverse(),
				(r += t.join("") + "-"),
				(t = e.slice(6, 8)),
				t.reverse(),
				(r += t.join("") + "-"),
				(t = e.slice(8, 10)),
				(r += t.join("") + "-"),
				(t = e.slice(10, 16)),
				(r += t.join(""))
			);
		}
		function o(e) {
			return (e = e.slice(-4, -2)), e[0].toString(16) + e[1].toString(16);
		}
		function a(e) {
			return e.reduce(function (e, r) {
				return 59 === e && (e = ";"), String(e) + String(r);
			});
		}
		function c(e) {
			return e.reduce(function (e, r) {
				return String(e) + String(r);
			});
		}
		function u(e) {
			var r = "";
			return (
				e.map(function (e) {
					r += String.fromCharCode(e).replace(String.fromCharCode(0), "");
				}),
				r
			);
		}
		Object.defineProperty(r, "__esModule", { value: !0 }), (r.parsePRIV = n);
	},
	function (e, r, t) {
		"use strict";
		function n(e, r, t) {
			for (var n = "", i = e; i < r; i++) n += String.fromCharCode(t[i]);
			return n;
		}
		function i(e, r) {
			var t = 0,
				i = void 0,
				o = "";
			if (0 === r && 1 !== e[0])
				for (; t < e.length;)
					e[t] < 127
						? ((o += String.fromCharCode(e[t])), t++)
						: ((i = e.slice(t, t + 2)), 1 == i.length ? ((o += new TextDecoder("iso-8859-1").decode(i.buffer)), t++) : ((i = new Uint16Array(i.buffer)), (o += new TextDecoder("gbk").decode(i.buffer)), (t += 2)));
			return (1 === r || (1 === e[0] && 0 === r)) && (-1 !== e.lastIndexOf(254) && ((e = e.slice(e.lastIndexOf(254) + 1)), (e = new Uint16Array(e.buffer))), (o = n(0, e.length, e))), (o = a(o));
		}
		function o(e) {
			var r = [255, 216],
				t = "jpeg";
			"PNG" === n(15, 18, e) && ((r[0] = 137), (r[1] = 80), (t = "png"));
			for (var i = 0; i < e.length && (r[0] !== e[i] || r[1] !== e[i + 1]);) i++;
			return { i: i, format: t };
		}
		function a(e) {
			var r = [String.fromCharCode(0), String.fromCharCode(255), "\f"],
				t = "",
				n = !0,
				i = !1,
				o = void 0;
			try {
				for (var a, c = e[Symbol.iterator](); !(n = (a = c.next()).done); n = !0) {
					var u = a.value;
					t += -1 !== r.indexOf(u) ? "" : u;
				}
			} catch (e) {
				(i = !0), (o = e);
			} finally {
				try {
					!n && c.return && c.return();
				} finally {
					if (i) throw o;
				}
			}
			return t;
		}
		Object.defineProperty(r, "__esModule", { value: !0 }), (r.getStr = n), (r.getTagData = i), (r.getImgIndex = o), (r.filterStr = a);
	},
]);
