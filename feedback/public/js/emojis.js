/*! jQuery v2.2.4 | (c) jQuery Foundation | jquery.org/license */
!function (a, b) {
    "object" == typeof module && "object" == typeof module.exports ? module.exports = a.document ? b(a, !0) : function (a) {
        if (!a.document) throw new Error("jQuery requires a window with a document");
        return b(a);
    } : b(a);
}("undefined" != typeof window ? window : this, function (a, b) {
    var c = [], d = a.document, e = c.slice, f = c.concat, g = c.push, h = c.indexOf, i = {}, j = i.toString,
        k = i.hasOwnProperty, l = {}, m = "2.2.4", n = function (a, b) {
            return new n.fn.init(a, b);
        }, o = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, p = /^-ms-/, q = /-([\da-z])/gi, r = function (a, b) {
            return b.toUpperCase();
        };
    n.fn = n.prototype = {
        jquery: m, constructor: n, selector: "", length: 0, toArray: function () {
            return e.call(this);
        }, get: function (a) {
            return null != a ? 0 > a ? this[a + this.length] : this[a] : e.call(this);
        }, pushStack: function (a) {
            var b = n.merge(this.constructor(), a);
            return b.prevObject = this, b.context = this.context, b;
        }, each: function (a) {
            return n.each(this, a);
        }, map: function (a) {
            return this.pushStack(n.map(this, function (b, c) {
                return a.call(b, c, b);
            }));
        }, slice: function () {
            return this.pushStack(e.apply(this, arguments));
        }, first: function () {
            return this.eq(0);
        }, last: function () {
            return this.eq(-1);
        }, eq: function (a) {
            var b = this.length, c = +a + (0 > a ? b : 0);
            return this.pushStack(c >= 0 && b > c ? [this[c]] : []);
        }, end: function () {
            return this.prevObject || this.constructor();
        }, push: g, sort: c.sort, splice: c.splice
    }, n.extend = n.fn.extend = function () {
        var a, b, c, d, e, f, g = arguments[0] || {}, h = 1, i = arguments.length, j = !1;
        for ("boolean" == typeof g && (j = g, g = arguments[h] || {}, h++), "object" == typeof g || n.isFunction(g) || (g = {}), h === i && (g = this, h--); i > h; h++) if (null != (a = arguments[h])) for (b in a) c = g[b], d = a[b], g !== d && (j && d && (n.isPlainObject(d) || (e = n.isArray(d))) ? (e ? (e = !1, f = c && n.isArray(c) ? c : []) : f = c && n.isPlainObject(c) ? c : {}, g[b] = n.extend(j, f, d)) : void 0 !== d && (g[b] = d));
        return g;
    }, n.extend({
        expando: "jQuery" + (m + Math.random()).replace(/\D/g, ""), isReady: !0, error: function (a) {
            throw new Error(a);
        }, noop: function () {
        }, isFunction: function (a) {
            return "function" === n.type(a);
        }, isArray: Array.isArray, isWindow: function (a) {
            return null != a && a === a.window;
        }, isNumeric: function (a) {
            var b = a && a.toString();
            return !n.isArray(a) && b - parseFloat(b) + 1 >= 0;
        }, isPlainObject: function (a) {
            var b;
            if ("object" !== n.type(a) || a.nodeType || n.isWindow(a)) return !1;
            if (a.constructor && !k.call(a, "constructor") && !k.call(a.constructor.prototype || {}, "isPrototypeOf")) return !1;
            for (b in a) ;
            return void 0 === b || k.call(a, b);
        }, isEmptyObject: function (a) {
            var b;
            for (b in a) return !1;
            return !0;
        }, type: function (a) {
            return null == a ? a + "" : "object" == typeof a || "function" == typeof a ? i[j.call(a)] || "object" : typeof a;
        }, globalEval: function (a) {
            var b, c = eval;
            a = n.trim(a), a && (1 === a.indexOf("use strict") ? (b = d.createElement("script"), b.text = a, d.head.appendChild(b).parentNode.removeChild(b)) : c(a));
        }, camelCase: function (a) {
            return a.replace(p, "ms-").replace(q, r);
        }, nodeName: function (a, b) {
            return a.nodeName && a.nodeName.toLowerCase() === b.toLowerCase();
        }, each: function (a, b) {
            var c, d = 0;
            if (s(a)) {
                for (c = a.length; c > d; d++) if (b.call(a[d], d, a[d]) === !1) break;
            } else for (d in a) if (b.call(a[d], d, a[d]) === !1) break;
            return a;
        }, trim: function (a) {
            return null == a ? "" : (a + "").replace(o, "");
        }, makeArray: function (a, b) {
            var c = b || [];
            return null != a && (s(Object(a)) ? n.merge(c, "string" == typeof a ? [a] : a) : g.call(c, a)), c;
        }, inArray: function (a, b, c) {
            return null == b ? -1 : h.call(b, a, c);
        }, merge: function (a, b) {
            for (var c = +b.length, d = 0, e = a.length; c > d; d++) a[e++] = b[d];
            return a.length = e, a;
        }, grep: function (a, b, c) {
            for (var d, e = [], f = 0, g = a.length, h = !c; g > f; f++) d = !b(a[f], f), d !== h && e.push(a[f]);
            return e;
        }, map: function (a, b, c) {
            var d, e, g = 0, h = [];
            if (s(a)) for (d = a.length; d > g; g++) e = b(a[g], g, c), null != e && h.push(e); else for (g in a) e = b(a[g], g, c), null != e && h.push(e);
            return f.apply([], h);
        }, guid: 1, proxy: function (a, b) {
            var c, d, f;
            return "string" == typeof b && (c = a[b], b = a, a = c), n.isFunction(a) ? (d = e.call(arguments, 2), f = function () {
                return a.apply(b || this, d.concat(e.call(arguments)));
            }, f.guid = a.guid = a.guid || n.guid++, f) : void 0;
        }, now: Date.now, support: l
    }), "function" == typeof Symbol && (n.fn[Symbol.iterator] = c[Symbol.iterator]), n.each("Boolean Number String Function Array Date RegExp Object Error Symbol".split(" "), function (a, b) {
        i["[object " + b + "]"] = b.toLowerCase();
    });

    function s(a) {
        var b = !!a && "length" in a && a.length, c = n.type(a);
        return "function" === c || n.isWindow(a) ? !1 : "array" === c || 0 === b || "number" == typeof b && b > 0 && b - 1 in a;
    }

    var t = function (a) {
        var b, c, d, e, f, g, h, i, j, k, l, m, n, o, p, q, r, s, t, u = "sizzle" + 1 * new Date, v = a.document, w = 0,
            x = 0, y = ga(), z = ga(), A = ga(), B = function (a, b) {
                return a === b && (l = !0), 0;
            }, C = 1 << 31, D = {}.hasOwnProperty, E = [], F = E.pop, G = E.push, H = E.push, I = E.slice,
            J = function (a, b) {
                for (var c = 0, d = a.length; d > c; c++) if (a[c] === b) return c;
                return -1;
            },
            K = "checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped",
            L = "[\\x20\\t\\r\\n\\f]", M = "(?:\\\\.|[\\w-]|[^\\x00-\\xa0])+",
            N = "\\[" + L + "*(" + M + ")(?:" + L + "*([*^$|!~]?=)" + L + "*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|(" + M + "))|)" + L + "*\\]",
            O = ":(" + M + ")(?:\\((('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|((?:\\\\.|[^\\\\()[\\]]|" + N + ")*)|.*)\\)|)",
            P = new RegExp(L + "+", "g"), Q = new RegExp("^" + L + "+|((?:^|[^\\\\])(?:\\\\.)*)" + L + "+$", "g"),
            R = new RegExp("^" + L + "*," + L + "*"), S = new RegExp("^" + L + "*([>+~]|" + L + ")" + L + "*"),
            T = new RegExp("=" + L + "*([^\\]'\"]*?)" + L + "*\\]", "g"), U = new RegExp(O),
            V = new RegExp("^" + M + "$"), W = {
                ID: new RegExp("^#(" + M + ")"),
                CLASS: new RegExp("^\\.(" + M + ")"),
                TAG: new RegExp("^(" + M + "|[*])"),
                ATTR: new RegExp("^" + N),
                PSEUDO: new RegExp("^" + O),
                CHILD: new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\(" + L + "*(even|odd|(([+-]|)(\\d*)n|)" + L + "*(?:([+-]|)" + L + "*(\\d+)|))" + L + "*\\)|)", "i"),
                bool: new RegExp("^(?:" + K + ")$", "i"),
                needsContext: new RegExp("^" + L + "*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\(" + L + "*((?:-\\d)?\\d*)" + L + "*\\)|)(?=[^-]|$)", "i")
            }, X = /^(?:input|select|textarea|button)$/i, Y = /^h\d$/i, Z = /^[^{]+\{\s*\[native \w/,
            $ = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/, _ = /[+~]/, aa = /'|\\/g,
            ba = new RegExp("\\\\([\\da-f]{1,6}" + L + "?|(" + L + ")|.)", "ig"), ca = function (a, b, c) {
                var d = "0x" + b - 65536;
                return d !== d || c ? b : 0 > d ? String.fromCharCode(d + 65536) : String.fromCharCode(d >> 10 | 55296, 1023 & d | 56320);
            }, da = function () {
                m();
            };
        try {
            H.apply(E = I.call(v.childNodes), v.childNodes), E[v.childNodes.length].nodeType;
        } catch (ea) {
            H = {
                apply: E.length ? function (a, b) {
                    G.apply(a, I.call(b));
                } : function (a, b) {
                    var c = a.length, d = 0;
                    while (a[c++] = b[d++]) ;
                    a.length = c - 1;
                }
            };
        }

        function fa(a, b, d, e) {
            var f, h, j, k, l, o, r, s, w = b && b.ownerDocument, x = b ? b.nodeType : 9;
            if (d = d || [], "string" != typeof a || !a || 1 !== x && 9 !== x && 11 !== x) return d;
            if (!e && ((b ? b.ownerDocument || b : v) !== n && m(b), b = b || n, p)) {
                if (11 !== x && (o = $.exec(a))) if (f = o[1]) {
                    if (9 === x) {
                        if (!(j = b.getElementById(f))) return d;
                        if (j.id === f) return d.push(j), d;
                    } else if (w && (j = w.getElementById(f)) && t(b, j) && j.id === f) return d.push(j), d;
                } else {
                    if (o[2]) return H.apply(d, b.getElementsByTagName(a)), d;
                    if ((f = o[3]) && c.getElementsByClassName && b.getElementsByClassName) return H.apply(d, b.getElementsByClassName(f)), d;
                }
                if (c.qsa && !A[a + " "] && (!q || !q.test(a))) {
                    if (1 !== x) w = b, s = a; else if ("object" !== b.nodeName.toLowerCase()) {
                        (k = b.getAttribute("id")) ? k = k.replace(aa, "\\$&") : b.setAttribute("id", k = u), r = g(a), h = r.length, l = V.test(k) ? "#" + k : "[id='" + k + "']";
                        while (h--) r[h] = l + " " + qa(r[h]);
                        s = r.join(","), w = _.test(a) && oa(b.parentNode) || b;
                    }
                    if (s) try {
                        return H.apply(d, w.querySelectorAll(s)), d;
                    } catch (y) {
                    } finally {
                        k === u && b.removeAttribute("id");
                    }
                }
            }
            return i(a.replace(Q, "$1"), b, d, e);
        }

        function ga() {
            var a = [];

            function b(c, e) {
                return a.push(c + " ") > d.cacheLength && delete b[a.shift()], b[c + " "] = e;
            }

            return b;
        }

        function ha(a) {
            return a[u] = !0, a;
        }

        function ia(a) {
            var b = n.createElement("div");
            try {
                return !!a(b);
            } catch (c) {
                return !1;
            } finally {
                b.parentNode && b.parentNode.removeChild(b), b = null;
            }
        }

        function ja(a, b) {
            var c = a.split("|"), e = c.length;
            while (e--) d.attrHandle[c[e]] = b;
        }

        function ka(a, b) {
            var c = b && a,
                d = c && 1 === a.nodeType && 1 === b.nodeType && (~b.sourceIndex || C) - (~a.sourceIndex || C);
            if (d) return d;
            if (c) while (c = c.nextSibling) if (c === b) return -1;
            return a ? 1 : -1;
        }

        function la(a) {
            return function (b) {
                var c = b.nodeName.toLowerCase();
                return "input" === c && b.type === a;
            };
        }

        function ma(a) {
            return function (b) {
                var c = b.nodeName.toLowerCase();
                return ("input" === c || "button" === c) && b.type === a;
            };
        }

        function na(a) {
            return ha(function (b) {
                return b = +b, ha(function (c, d) {
                    var e, f = a([], c.length, b), g = f.length;
                    while (g--) c[e = f[g]] && (c[e] = !(d[e] = c[e]));
                });
            });
        }

        function oa(a) {
            return a && "undefined" != typeof a.getElementsByTagName && a;
        }

        c = fa.support = {}, f = fa.isXML = function (a) {
            var b = a && (a.ownerDocument || a).documentElement;
            return b ? "HTML" !== b.nodeName : !1;
        }, m = fa.setDocument = function (a) {
            var b, e, g = a ? a.ownerDocument || a : v;
            return g !== n && 9 === g.nodeType && g.documentElement ? (n = g, o = n.documentElement, p = !f(n), (e = n.defaultView) && e.top !== e && (e.addEventListener ? e.addEventListener("unload", da, !1) : e.attachEvent && e.attachEvent("onunload", da)), c.attributes = ia(function (a) {
                return a.className = "i", !a.getAttribute("className");
            }), c.getElementsByTagName = ia(function (a) {
                return a.appendChild(n.createComment("")), !a.getElementsByTagName("*").length;
            }), c.getElementsByClassName = Z.test(n.getElementsByClassName), c.getById = ia(function (a) {
                return o.appendChild(a).id = u, !n.getElementsByName || !n.getElementsByName(u).length;
            }), c.getById ? (d.find.ID = function (a, b) {
                if ("undefined" != typeof b.getElementById && p) {
                    var c = b.getElementById(a);
                    return c ? [c] : [];
                }
            }, d.filter.ID = function (a) {
                var b = a.replace(ba, ca);
                return function (a) {
                    return a.getAttribute("id") === b;
                };
            }) : (delete d.find.ID, d.filter.ID = function (a) {
                var b = a.replace(ba, ca);
                return function (a) {
                    var c = "undefined" != typeof a.getAttributeNode && a.getAttributeNode("id");
                    return c && c.value === b;
                };
            }), d.find.TAG = c.getElementsByTagName ? function (a, b) {
                return "undefined" != typeof b.getElementsByTagName ? b.getElementsByTagName(a) : c.qsa ? b.querySelectorAll(a) : void 0;
            } : function (a, b) {
                var c, d = [], e = 0, f = b.getElementsByTagName(a);
                if ("*" === a) {
                    while (c = f[e++]) 1 === c.nodeType && d.push(c);
                    return d;
                }
                return f;
            }, d.find.CLASS = c.getElementsByClassName && function (a, b) {
                return "undefined" != typeof b.getElementsByClassName && p ? b.getElementsByClassName(a) : void 0;
            }, r = [], q = [], (c.qsa = Z.test(n.querySelectorAll)) && (ia(function (a) {
                o.appendChild(a).innerHTML = "<a id='" + u + "'></a><select id='" + u + "-\r\\' msallowcapture=''><option selected=''></option></select>", a.querySelectorAll("[msallowcapture^='']").length && q.push("[*^$]=" + L + "*(?:''|\"\")"), a.querySelectorAll("[selected]").length || q.push("\\[" + L + "*(?:value|" + K + ")"), a.querySelectorAll("[id~=" + u + "-]").length || q.push("~="), a.querySelectorAll(":checked").length || q.push(":checked"), a.querySelectorAll("a#" + u + "+*").length || q.push(".#.+[+~]");
            }), ia(function (a) {
                var b = n.createElement("input");
                b.setAttribute("type", "hidden"), a.appendChild(b).setAttribute("name", "D"), a.querySelectorAll("[name=d]").length && q.push("name" + L + "*[*^$|!~]?="), a.querySelectorAll(":enabled").length || q.push(":enabled", ":disabled"), a.querySelectorAll("*,:x"), q.push(",.*:");
            })), (c.matchesSelector = Z.test(s = o.matches || o.webkitMatchesSelector || o.mozMatchesSelector || o.oMatchesSelector || o.msMatchesSelector)) && ia(function (a) {
                c.disconnectedMatch = s.call(a, "div"), s.call(a, "[s!='']:x"), r.push("!=", O);
            }), q = q.length && new RegExp(q.join("|")), r = r.length && new RegExp(r.join("|")), b = Z.test(o.compareDocumentPosition), t = b || Z.test(o.contains) ? function (a, b) {
                var c = 9 === a.nodeType ? a.documentElement : a, d = b && b.parentNode;
                return a === d || !(!d || 1 !== d.nodeType || !(c.contains ? c.contains(d) : a.compareDocumentPosition && 16 & a.compareDocumentPosition(d)));
            } : function (a, b) {
                if (b) while (b = b.parentNode) if (b === a) return !0;
                return !1;
            }, B = b ? function (a, b) {
                if (a === b) return l = !0, 0;
                var d = !a.compareDocumentPosition - !b.compareDocumentPosition;
                return d ? d : (d = (a.ownerDocument || a) === (b.ownerDocument || b) ? a.compareDocumentPosition(b) : 1, 1 & d || !c.sortDetached && b.compareDocumentPosition(a) === d ? a === n || a.ownerDocument === v && t(v, a) ? -1 : b === n || b.ownerDocument === v && t(v, b) ? 1 : k ? J(k, a) - J(k, b) : 0 : 4 & d ? -1 : 1);
            } : function (a, b) {
                if (a === b) return l = !0, 0;
                var c, d = 0, e = a.parentNode, f = b.parentNode, g = [a], h = [b];
                if (!e || !f) return a === n ? -1 : b === n ? 1 : e ? -1 : f ? 1 : k ? J(k, a) - J(k, b) : 0;
                if (e === f) return ka(a, b);
                c = a;
                while (c = c.parentNode) g.unshift(c);
                c = b;
                while (c = c.parentNode) h.unshift(c);
                while (g[d] === h[d]) d++;
                return d ? ka(g[d], h[d]) : g[d] === v ? -1 : h[d] === v ? 1 : 0;
            }, n) : n;
        }, fa.matches = function (a, b) {
            return fa(a, null, null, b);
        }, fa.matchesSelector = function (a, b) {
            if ((a.ownerDocument || a) !== n && m(a), b = b.replace(T, "='$1']"), c.matchesSelector && p && !A[b + " "] && (!r || !r.test(b)) && (!q || !q.test(b))) try {
                var d = s.call(a, b);
                if (d || c.disconnectedMatch || a.document && 11 !== a.document.nodeType) return d;
            } catch (e) {
            }
            return fa(b, n, null, [a]).length > 0;
        }, fa.contains = function (a, b) {
            return (a.ownerDocument || a) !== n && m(a), t(a, b);
        }, fa.attr = function (a, b) {
            (a.ownerDocument || a) !== n && m(a);
            var e = d.attrHandle[b.toLowerCase()],
                f = e && D.call(d.attrHandle, b.toLowerCase()) ? e(a, b, !p) : void 0;
            return void 0 !== f ? f : c.attributes || !p ? a.getAttribute(b) : (f = a.getAttributeNode(b)) && f.specified ? f.value : null;
        }, fa.error = function (a) {
            throw new Error("Syntax error, unrecognized expression: " + a);
        }, fa.uniqueSort = function (a) {
            var b, d = [], e = 0, f = 0;
            if (l = !c.detectDuplicates, k = !c.sortStable && a.slice(0), a.sort(B), l) {
                while (b = a[f++]) b === a[f] && (e = d.push(f));
                while (e--) a.splice(d[e], 1);
            }
            return k = null, a;
        }, e = fa.getText = function (a) {
            var b, c = "", d = 0, f = a.nodeType;
            if (f) {
                if (1 === f || 9 === f || 11 === f) {
                    if ("string" == typeof a.textContent) return a.textContent;
                    for (a = a.firstChild; a; a = a.nextSibling) c += e(a);
                } else if (3 === f || 4 === f) return a.nodeValue;
            } else while (b = a[d++]) c += e(b);
            return c;
        }, d = fa.selectors = {
            cacheLength: 50,
            createPseudo: ha,
            match: W,
            attrHandle: {},
            find: {},
            relative: {
                ">": {dir: "parentNode", first: !0},
                " ": {dir: "parentNode"},
                "+": {dir: "previousSibling", first: !0},
                "~": {dir: "previousSibling"}
            },
            preFilter: {
                ATTR: function (a) {
                    return a[1] = a[1].replace(ba, ca), a[3] = (a[3] || a[4] || a[5] || "").replace(ba, ca), "~=" === a[2] && (a[3] = " " + a[3] + " "), a.slice(0, 4);
                }, CHILD: function (a) {
                    return a[1] = a[1].toLowerCase(), "nth" === a[1].slice(0, 3) ? (a[3] || fa.error(a[0]), a[4] = +(a[4] ? a[5] + (a[6] || 1) : 2 * ("even" === a[3] || "odd" === a[3])), a[5] = +(a[7] + a[8] || "odd" === a[3])) : a[3] && fa.error(a[0]), a;
                }, PSEUDO: function (a) {
                    var b, c = !a[6] && a[2];
                    return W.CHILD.test(a[0]) ? null : (a[3] ? a[2] = a[4] || a[5] || "" : c && U.test(c) && (b = g(c, !0)) && (b = c.indexOf(")", c.length - b) - c.length) && (a[0] = a[0].slice(0, b), a[2] = c.slice(0, b)), a.slice(0, 3));
                }
            },
            filter: {
                TAG: function (a) {
                    var b = a.replace(ba, ca).toLowerCase();
                    return "*" === a ? function () {
                        return !0;
                    } : function (a) {
                        return a.nodeName && a.nodeName.toLowerCase() === b;
                    };
                }, CLASS: function (a) {
                    var b = y[a + " "];
                    return b || (b = new RegExp("(^|" + L + ")" + a + "(" + L + "|$)")) && y(a, function (a) {
                        return b.test("string" == typeof a.className && a.className || "undefined" != typeof a.getAttribute && a.getAttribute("class") || "");
                    });
                }, ATTR: function (a, b, c) {
                    return function (d) {
                        var e = fa.attr(d, a);
                        return null == e ? "!=" === b : b ? (e += "", "=" === b ? e === c : "!=" === b ? e !== c : "^=" === b ? c && 0 === e.indexOf(c) : "*=" === b ? c && e.indexOf(c) > -1 : "$=" === b ? c && e.slice(-c.length) === c : "~=" === b ? (" " + e.replace(P, " ") + " ").indexOf(c) > -1 : "|=" === b ? e === c || e.slice(0, c.length + 1) === c + "-" : !1) : !0;
                    };
                }, CHILD: function (a, b, c, d, e) {
                    var f = "nth" !== a.slice(0, 3), g = "last" !== a.slice(-4), h = "of-type" === b;
                    return 1 === d && 0 === e ? function (a) {
                        return !!a.parentNode;
                    } : function (b, c, i) {
                        var j, k, l, m, n, o, p = f !== g ? "nextSibling" : "previousSibling", q = b.parentNode,
                            r = h && b.nodeName.toLowerCase(), s = !i && !h, t = !1;
                        if (q) {
                            if (f) {
                                while (p) {
                                    m = b;
                                    while (m = m[p]) if (h ? m.nodeName.toLowerCase() === r : 1 === m.nodeType) return !1;
                                    o = p = "only" === a && !o && "nextSibling";
                                }
                                return !0;
                            }
                            if (o = [g ? q.firstChild : q.lastChild], g && s) {
                                m = q, l = m[u] || (m[u] = {}), k = l[m.uniqueID] || (l[m.uniqueID] = {}), j = k[a] || [], n = j[0] === w && j[1], t = n && j[2], m = n && q.childNodes[n];
                                while (m = ++n && m && m[p] || (t = n = 0) || o.pop()) if (1 === m.nodeType && ++t && m === b) {
                                    k[a] = [w, n, t];
                                    break;
                                }
                            } else if (s && (m = b, l = m[u] || (m[u] = {}), k = l[m.uniqueID] || (l[m.uniqueID] = {}), j = k[a] || [], n = j[0] === w && j[1], t = n), t === !1) while (m = ++n && m && m[p] || (t = n = 0) || o.pop()) if ((h ? m.nodeName.toLowerCase() === r : 1 === m.nodeType) && ++t && (s && (l = m[u] || (m[u] = {}), k = l[m.uniqueID] || (l[m.uniqueID] = {}), k[a] = [w, t]), m === b)) break;
                            return t -= e, t === d || t % d === 0 && t / d >= 0;
                        }
                    };
                }, PSEUDO: function (a, b) {
                    var c, e = d.pseudos[a] || d.setFilters[a.toLowerCase()] || fa.error("unsupported pseudo: " + a);
                    return e[u] ? e(b) : e.length > 1 ? (c = [a, a, "", b], d.setFilters.hasOwnProperty(a.toLowerCase()) ? ha(function (a, c) {
                        var d, f = e(a, b), g = f.length;
                        while (g--) d = J(a, f[g]), a[d] = !(c[d] = f[g]);
                    }) : function (a) {
                        return e(a, 0, c);
                    }) : e;
                }
            },
            pseudos: {
                not: ha(function (a) {
                    var b = [], c = [], d = h(a.replace(Q, "$1"));
                    return d[u] ? ha(function (a, b, c, e) {
                        var f, g = d(a, null, e, []), h = a.length;
                        while (h--) (f = g[h]) && (a[h] = !(b[h] = f));
                    }) : function (a, e, f) {
                        return b[0] = a, d(b, null, f, c), b[0] = null, !c.pop();
                    };
                }), has: ha(function (a) {
                    return function (b) {
                        return fa(a, b).length > 0;
                    };
                }), contains: ha(function (a) {
                    return a = a.replace(ba, ca), function (b) {
                        return (b.textContent || b.innerText || e(b)).indexOf(a) > -1;
                    };
                }), lang: ha(function (a) {
                    return V.test(a || "") || fa.error("unsupported lang: " + a), a = a.replace(ba, ca).toLowerCase(), function (b) {
                        var c;
                        do if (c = p ? b.lang : b.getAttribute("xml:lang") || b.getAttribute("lang")) return c = c.toLowerCase(), c === a || 0 === c.indexOf(a + "-"); while ((b = b.parentNode) && 1 === b.nodeType);
                        return !1;
                    };
                }), target: function (b) {
                    var c = a.location && a.location.hash;
                    return c && c.slice(1) === b.id;
                }, root: function (a) {
                    return a === o;
                }, focus: function (a) {
                    return a === n.activeElement && (!n.hasFocus || n.hasFocus()) && !!(a.type || a.href || ~a.tabIndex);
                }, enabled: function (a) {
                    return a.disabled === !1;
                }, disabled: function (a) {
                    return a.disabled === !0;
                }, checked: function (a) {
                    var b = a.nodeName.toLowerCase();
                    return "input" === b && !!a.checked || "option" === b && !!a.selected;
                }, selected: function (a) {
                    return a.parentNode && a.parentNode.selectedIndex, a.selected === !0;
                }, empty: function (a) {
                    for (a = a.firstChild; a; a = a.nextSibling) if (a.nodeType < 6) return !1;
                    return !0;
                }, parent: function (a) {
                    return !d.pseudos.empty(a);
                }, header: function (a) {
                    return Y.test(a.nodeName);
                }, input: function (a) {
                    return X.test(a.nodeName);
                }, button: function (a) {
                    var b = a.nodeName.toLowerCase();
                    return "input" === b && "button" === a.type || "button" === b;
                }, text: function (a) {
                    var b;
                    return "input" === a.nodeName.toLowerCase() && "text" === a.type && (null == (b = a.getAttribute("type")) || "text" === b.toLowerCase());
                }, first: na(function () {
                    return [0];
                }), last: na(function (a, b) {
                    return [b - 1];
                }), eq: na(function (a, b, c) {
                    return [0 > c ? c + b : c];
                }), even: na(function (a, b) {
                    for (var c = 0; b > c; c += 2) a.push(c);
                    return a;
                }), odd: na(function (a, b) {
                    for (var c = 1; b > c; c += 2) a.push(c);
                    return a;
                }), lt: na(function (a, b, c) {
                    for (var d = 0 > c ? c + b : c; --d >= 0;) a.push(d);
                    return a;
                }), gt: na(function (a, b, c) {
                    for (var d = 0 > c ? c + b : c; ++d < b;) a.push(d);
                    return a;
                })
            }
        }, d.pseudos.nth = d.pseudos.eq;
        for (b in{radio: !0, checkbox: !0, file: !0, password: !0, image: !0}) d.pseudos[b] = la(b);
        for (b in{submit: !0, reset: !0}) d.pseudos[b] = ma(b);

        function pa() {
        }

        pa.prototype = d.filters = d.pseudos, d.setFilters = new pa, g = fa.tokenize = function (a, b) {
            var c, e, f, g, h, i, j, k = z[a + " "];
            if (k) return b ? 0 : k.slice(0);
            h = a, i = [], j = d.preFilter;
            while (h) {
                c && !(e = R.exec(h)) || (e && (h = h.slice(e[0].length) || h), i.push(f = [])), c = !1, (e = S.exec(h)) && (c = e.shift(), f.push({
                    value: c,
                    type: e[0].replace(Q, " ")
                }), h = h.slice(c.length));
                for (g in d.filter) !(e = W[g].exec(h)) || j[g] && !(e = j[g](e)) || (c = e.shift(), f.push({
                    value: c,
                    type: g,
                    matches: e
                }), h = h.slice(c.length));
                if (!c) break;
            }
            return b ? h.length : h ? fa.error(a) : z(a, i).slice(0);
        };

        function qa(a) {
            for (var b = 0, c = a.length, d = ""; c > b; b++) d += a[b].value;
            return d;
        }

        function ra(a, b, c) {
            var d = b.dir, e = c && "parentNode" === d, f = x++;
            return b.first ? function (b, c, f) {
                while (b = b[d]) if (1 === b.nodeType || e) return a(b, c, f);
            } : function (b, c, g) {
                var h, i, j, k = [w, f];
                if (g) {
                    while (b = b[d]) if ((1 === b.nodeType || e) && a(b, c, g)) return !0;
                } else while (b = b[d]) if (1 === b.nodeType || e) {
                    if (j = b[u] || (b[u] = {}), i = j[b.uniqueID] || (j[b.uniqueID] = {}), (h = i[d]) && h[0] === w && h[1] === f) return k[2] = h[2];
                    if (i[d] = k, k[2] = a(b, c, g)) return !0;
                }
            };
        }

        function sa(a) {
            return a.length > 1 ? function (b, c, d) {
                var e = a.length;
                while (e--) if (!a[e](b, c, d)) return !1;
                return !0;
            } : a[0];
        }

        function ta(a, b, c) {
            for (var d = 0, e = b.length; e > d; d++) fa(a, b[d], c);
            return c;
        }

        function ua(a, b, c, d, e) {
            for (var f, g = [], h = 0, i = a.length, j = null != b; i > h; h++) (f = a[h]) && (c && !c(f, d, e) || (g.push(f), j && b.push(h)));
            return g;
        }

        function va(a, b, c, d, e, f) {
            return d && !d[u] && (d = va(d)), e && !e[u] && (e = va(e, f)), ha(function (f, g, h, i) {
                var j, k, l, m = [], n = [], o = g.length, p = f || ta(b || "*", h.nodeType ? [h] : h, []),
                    q = !a || !f && b ? p : ua(p, m, a, h, i), r = c ? e || (f ? a : o || d) ? [] : g : q;
                if (c && c(q, r, h, i), d) {
                    j = ua(r, n), d(j, [], h, i), k = j.length;
                    while (k--) (l = j[k]) && (r[n[k]] = !(q[n[k]] = l));
                }
                if (f) {
                    if (e || a) {
                        if (e) {
                            j = [], k = r.length;
                            while (k--) (l = r[k]) && j.push(q[k] = l);
                            e(null, r = [], j, i);
                        }
                        k = r.length;
                        while (k--) (l = r[k]) && (j = e ? J(f, l) : m[k]) > -1 && (f[j] = !(g[j] = l));
                    }
                } else r = ua(r === g ? r.splice(o, r.length) : r), e ? e(null, g, r, i) : H.apply(g, r);
            });
        }

        function wa(a) {
            for (var b, c, e, f = a.length, g = d.relative[a[0].type], h = g || d.relative[" "], i = g ? 1 : 0, k = ra(function (a) {
                return a === b;
            }, h, !0), l = ra(function (a) {
                return J(b, a) > -1;
            }, h, !0), m = [function (a, c, d) {
                var e = !g && (d || c !== j) || ((b = c).nodeType ? k(a, c, d) : l(a, c, d));
                return b = null, e;
            }]; f > i; i++) if (c = d.relative[a[i].type]) m = [ra(sa(m), c)]; else {
                if (c = d.filter[a[i].type].apply(null, a[i].matches), c[u]) {
                    for (e = ++i; f > e; e++) if (d.relative[a[e].type]) break;
                    return va(i > 1 && sa(m), i > 1 && qa(a.slice(0, i - 1).concat({value: " " === a[i - 2].type ? "*" : ""})).replace(Q, "$1"), c, e > i && wa(a.slice(i, e)), f > e && wa(a = a.slice(e)), f > e && qa(a));
                }
                m.push(c);
            }
            return sa(m);
        }

        function xa(a, b) {
            var c = b.length > 0, e = a.length > 0, f = function (f, g, h, i, k) {
                var l, o, q, r = 0, s = "0", t = f && [], u = [], v = j, x = f || e && d.find.TAG("*", k),
                    y = w += null == v ? 1 : Math.random() || .1, z = x.length;
                for (k && (j = g === n || g || k); s !== z && null != (l = x[s]); s++) {
                    if (e && l) {
                        o = 0, g || l.ownerDocument === n || (m(l), h = !p);
                        while (q = a[o++]) if (q(l, g || n, h)) {
                            i.push(l);
                            break;
                        }
                        k && (w = y);
                    }
                    c && ((l = !q && l) && r--, f && t.push(l));
                }
                if (r += s, c && s !== r) {
                    o = 0;
                    while (q = b[o++]) q(t, u, g, h);
                    if (f) {
                        if (r > 0) while (s--) t[s] || u[s] || (u[s] = F.call(i));
                        u = ua(u);
                    }
                    H.apply(i, u), k && !f && u.length > 0 && r + b.length > 1 && fa.uniqueSort(i);
                }
                return k && (w = y, j = v), t;
            };
            return c ? ha(f) : f;
        }

        return h = fa.compile = function (a, b) {
            var c, d = [], e = [], f = A[a + " "];
            if (!f) {
                b || (b = g(a)), c = b.length;
                while (c--) f = wa(b[c]), f[u] ? d.push(f) : e.push(f);
                f = A(a, xa(e, d)), f.selector = a;
            }
            return f;
        }, i = fa.select = function (a, b, e, f) {
            var i, j, k, l, m, n = "function" == typeof a && a, o = !f && g(a = n.selector || a);
            if (e = e || [], 1 === o.length) {
                if (j = o[0] = o[0].slice(0), j.length > 2 && "ID" === (k = j[0]).type && c.getById && 9 === b.nodeType && p && d.relative[j[1].type]) {
                    if (b = (d.find.ID(k.matches[0].replace(ba, ca), b) || [])[0], !b) return e;
                    n && (b = b.parentNode), a = a.slice(j.shift().value.length);
                }
                i = W.needsContext.test(a) ? 0 : j.length;
                while (i--) {
                    if (k = j[i], d.relative[l = k.type]) break;
                    if ((m = d.find[l]) && (f = m(k.matches[0].replace(ba, ca), _.test(j[0].type) && oa(b.parentNode) || b))) {
                        if (j.splice(i, 1), a = f.length && qa(j), !a) return H.apply(e, f), e;
                        break;
                    }
                }
            }
            return (n || h(a, o))(f, b, !p, e, !b || _.test(a) && oa(b.parentNode) || b), e;
        }, c.sortStable = u.split("").sort(B).join("") === u, c.detectDuplicates = !!l, m(), c.sortDetached = ia(function (a) {
            return 1 & a.compareDocumentPosition(n.createElement("div"));
        }), ia(function (a) {
            return a.innerHTML = "<a href='#'></a>", "#" === a.firstChild.getAttribute("href");
        }) || ja("type|href|height|width", function (a, b, c) {
            return c ? void 0 : a.getAttribute(b, "type" === b.toLowerCase() ? 1 : 2);
        }), c.attributes && ia(function (a) {
            return a.innerHTML = "<input/>", a.firstChild.setAttribute("value", ""), "" === a.firstChild.getAttribute("value");
        }) || ja("value", function (a, b, c) {
            return c || "input" !== a.nodeName.toLowerCase() ? void 0 : a.defaultValue;
        }), ia(function (a) {
            return null == a.getAttribute("disabled");
        }) || ja(K, function (a, b, c) {
            var d;
            return c ? void 0 : a[b] === !0 ? b.toLowerCase() : (d = a.getAttributeNode(b)) && d.specified ? d.value : null;
        }), fa;
    }(a);
    n.find = t, n.expr = t.selectors, n.expr[":"] = n.expr.pseudos, n.uniqueSort = n.unique = t.uniqueSort, n.text = t.getText, n.isXMLDoc = t.isXML, n.contains = t.contains;
    var u = function (a, b, c) {
        var d = [], e = void 0 !== c;
        while ((a = a[b]) && 9 !== a.nodeType) if (1 === a.nodeType) {
            if (e && n(a).is(c)) break;
            d.push(a);
        }
        return d;
    }, v = function (a, b) {
        for (var c = []; a; a = a.nextSibling) 1 === a.nodeType && a !== b && c.push(a);
        return c;
    }, w = n.expr.match.needsContext, x = /^<([\w-]+)\s*\/?>(?:<\/\1>|)$/, y = /^.[^:#\[\.,]*$/;

    function z(a, b, c) {
        if (n.isFunction(b)) return n.grep(a, function (a, d) {
            return !!b.call(a, d, a) !== c;
        });
        if (b.nodeType) return n.grep(a, function (a) {
            return a === b !== c;
        });
        if ("string" == typeof b) {
            if (y.test(b)) return n.filter(b, a, c);
            b = n.filter(b, a);
        }
        return n.grep(a, function (a) {
            return h.call(b, a) > -1 !== c;
        });
    }

    n.filter = function (a, b, c) {
        var d = b[0];
        return c && (a = ":not(" + a + ")"), 1 === b.length && 1 === d.nodeType ? n.find.matchesSelector(d, a) ? [d] : [] : n.find.matches(a, n.grep(b, function (a) {
            return 1 === a.nodeType;
        }));
    }, n.fn.extend({
        find: function (a) {
            var b, c = this.length, d = [], e = this;
            if ("string" != typeof a) return this.pushStack(n(a).filter(function () {
                for (b = 0; c > b; b++) if (n.contains(e[b], this)) return !0;
            }));
            for (b = 0; c > b; b++) n.find(a, e[b], d);
            return d = this.pushStack(c > 1 ? n.unique(d) : d), d.selector = this.selector ? this.selector + " " + a : a, d;
        }, filter: function (a) {
            return this.pushStack(z(this, a || [], !1));
        }, not: function (a) {
            return this.pushStack(z(this, a || [], !0));
        }, is: function (a) {
            return !!z(this, "string" == typeof a && w.test(a) ? n(a) : a || [], !1).length;
        }
    });
    var A, B = /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]*))$/, C = n.fn.init = function (a, b, c) {
        var e, f;
        if (!a) return this;
        if (c = c || A, "string" == typeof a) {
            if (e = "<" === a[0] && ">" === a[a.length - 1] && a.length >= 3 ? [null, a, null] : B.exec(a), !e || !e[1] && b) return !b || b.jquery ? (b || c).find(a) : this.constructor(b).find(a);
            if (e[1]) {
                if (b = b instanceof n ? b[0] : b, n.merge(this, n.parseHTML(e[1], b && b.nodeType ? b.ownerDocument || b : d, !0)), x.test(e[1]) && n.isPlainObject(b)) for (e in b) n.isFunction(this[e]) ? this[e](b[e]) : this.attr(e, b[e]);
                return this;
            }
            return f = d.getElementById(e[2]), f && f.parentNode && (this.length = 1, this[0] = f), this.context = d, this.selector = a, this;
        }
        return a.nodeType ? (this.context = this[0] = a, this.length = 1, this) : n.isFunction(a) ? void 0 !== c.ready ? c.ready(a) : a(n) : (void 0 !== a.selector && (this.selector = a.selector, this.context = a.context), n.makeArray(a, this));
    };
    C.prototype = n.fn, A = n(d);
    var D = /^(?:parents|prev(?:Until|All))/, E = {children: !0, contents: !0, next: !0, prev: !0};
    n.fn.extend({
        has: function (a) {
            var b = n(a, this), c = b.length;
            return this.filter(function () {
                for (var a = 0; c > a; a++) if (n.contains(this, b[a])) return !0;
            });
        }, closest: function (a, b) {
            for (var c, d = 0, e = this.length, f = [], g = w.test(a) || "string" != typeof a ? n(a, b || this.context) : 0; e > d; d++) for (c = this[d]; c && c !== b; c = c.parentNode) if (c.nodeType < 11 && (g ? g.index(c) > -1 : 1 === c.nodeType && n.find.matchesSelector(c, a))) {
                f.push(c);
                break;
            }
            return this.pushStack(f.length > 1 ? n.uniqueSort(f) : f);
        }, index: function (a) {
            return a ? "string" == typeof a ? h.call(n(a), this[0]) : h.call(this, a.jquery ? a[0] : a) : this[0] && this[0].parentNode ? this.first().prevAll().length : -1;
        }, add: function (a, b) {
            return this.pushStack(n.uniqueSort(n.merge(this.get(), n(a, b))));
        }, addBack: function (a) {
            return this.add(null == a ? this.prevObject : this.prevObject.filter(a));
        }
    });

    function F(a, b) {
        while ((a = a[b]) && 1 !== a.nodeType) ;
        return a;
    }

    n.each({
        parent: function (a) {
            var b = a.parentNode;
            return b && 11 !== b.nodeType ? b : null;
        }, parents: function (a) {
            return u(a, "parentNode");
        }, parentsUntil: function (a, b, c) {
            return u(a, "parentNode", c);
        }, next: function (a) {
            return F(a, "nextSibling");
        }, prev: function (a) {
            return F(a, "previousSibling");
        }, nextAll: function (a) {
            return u(a, "nextSibling");
        }, prevAll: function (a) {
            return u(a, "previousSibling");
        }, nextUntil: function (a, b, c) {
            return u(a, "nextSibling", c);
        }, prevUntil: function (a, b, c) {
            return u(a, "previousSibling", c);
        }, siblings: function (a) {
            return v((a.parentNode || {}).firstChild, a);
        }, children: function (a) {
            return v(a.firstChild);
        }, contents: function (a) {
            return a.contentDocument || n.merge([], a.childNodes);
        }
    }, function (a, b) {
        n.fn[a] = function (c, d) {
            var e = n.map(this, b, c);
            return "Until" !== a.slice(-5) && (d = c), d && "string" == typeof d && (e = n.filter(d, e)), this.length > 1 && (E[a] || n.uniqueSort(e), D.test(a) && e.reverse()), this.pushStack(e);
        };
    });
    var G = /\S+/g;

    function H(a) {
        var b = {};
        return n.each(a.match(G) || [], function (a, c) {
            b[c] = !0;
        }), b;
    }

    n.Callbacks = function (a) {
        a = "string" == typeof a ? H(a) : n.extend({}, a);
        var b, c, d, e, f = [], g = [], h = -1, i = function () {
            for (e = a.once, d = b = !0; g.length; h = -1) {
                c = g.shift();
                while (++h < f.length) f[h].apply(c[0], c[1]) === !1 && a.stopOnFalse && (h = f.length, c = !1);
            }
            a.memory || (c = !1), b = !1, e && (f = c ? [] : "");
        }, j = {
            add: function () {
                return f && (c && !b && (h = f.length - 1, g.push(c)), function d(b) {
                    n.each(b, function (b, c) {
                        n.isFunction(c) ? a.unique && j.has(c) || f.push(c) : c && c.length && "string" !== n.type(c) && d(c);
                    });
                }(arguments), c && !b && i()), this;
            }, remove: function () {
                return n.each(arguments, function (a, b) {
                    var c;
                    while ((c = n.inArray(b, f, c)) > -1) f.splice(c, 1), h >= c && h--;
                }), this;
            }, has: function (a) {
                return a ? n.inArray(a, f) > -1 : f.length > 0;
            }, empty: function () {
                return f && (f = []), this;
            }, disable: function () {
                return e = g = [], f = c = "", this;
            }, disabled: function () {
                return !f;
            }, lock: function () {
                return e = g = [], c || (f = c = ""), this;
            }, locked: function () {
                return !!e;
            }, fireWith: function (a, c) {
                return e || (c = c || [], c = [a, c.slice ? c.slice() : c], g.push(c), b || i()), this;
            }, fire: function () {
                return j.fireWith(this, arguments), this;
            }, fired: function () {
                return !!d;
            }
        };
        return j;
    }, n.extend({
        Deferred: function (a) {
            var b = [["resolve", "done", n.Callbacks("once memory"), "resolved"], ["reject", "fail", n.Callbacks("once memory"), "rejected"], ["notify", "progress", n.Callbacks("memory")]],
                c = "pending", d = {
                    state: function () {
                        return c;
                    }, always: function () {
                        return e.done(arguments).fail(arguments), this;
                    }, then: function () {
                        var a = arguments;
                        return n.Deferred(function (c) {
                            n.each(b, function (b, f) {
                                var g = n.isFunction(a[b]) && a[b];
                                e[f[1]](function () {
                                    var a = g && g.apply(this, arguments);
                                    a && n.isFunction(a.promise) ? a.promise().progress(c.notify).done(c.resolve).fail(c.reject) : c[f[0] + "With"](this === d ? c.promise() : this, g ? [a] : arguments);
                                });
                            }), a = null;
                        }).promise();
                    }, promise: function (a) {
                        return null != a ? n.extend(a, d) : d;
                    }
                }, e = {};
            return d.pipe = d.then, n.each(b, function (a, f) {
                var g = f[2], h = f[3];
                d[f[1]] = g.add, h && g.add(function () {
                    c = h;
                }, b[1 ^ a][2].disable, b[2][2].lock), e[f[0]] = function () {
                    return e[f[0] + "With"](this === e ? d : this, arguments), this;
                }, e[f[0] + "With"] = g.fireWith;
            }), d.promise(e), a && a.call(e, e), e;
        }, when: function (a) {
            var b = 0, c = e.call(arguments), d = c.length, f = 1 !== d || a && n.isFunction(a.promise) ? d : 0,
                g = 1 === f ? a : n.Deferred(), h = function (a, b, c) {
                    return function (d) {
                        b[a] = this, c[a] = arguments.length > 1 ? e.call(arguments) : d, c === i ? g.notifyWith(b, c) : --f || g.resolveWith(b, c);
                    };
                }, i, j, k;
            if (d > 1) for (i = new Array(d), j = new Array(d), k = new Array(d); d > b; b++) c[b] && n.isFunction(c[b].promise) ? c[b].promise().progress(h(b, j, i)).done(h(b, k, c)).fail(g.reject) : --f;
            return f || g.resolveWith(k, c), g.promise();
        }
    });
    var I;
    n.fn.ready = function (a) {
        return n.ready.promise().done(a), this;
    }, n.extend({
        isReady: !1, readyWait: 1, holdReady: function (a) {
            a ? n.readyWait++ : n.ready(!0);
        }, ready: function (a) {
            (a === !0 ? --n.readyWait : n.isReady) || (n.isReady = !0, a !== !0 && --n.readyWait > 0 || (I.resolveWith(d, [n]), n.fn.triggerHandler && (n(d).triggerHandler("ready"), n(d).off("ready"))));
        }
    });

    function J() {
        d.removeEventListener("DOMContentLoaded", J), a.removeEventListener("load", J), n.ready();
    }

    n.ready.promise = function (b) {
        return I || (I = n.Deferred(), "complete" === d.readyState || "loading" !== d.readyState && !d.documentElement.doScroll ? a.setTimeout(n.ready) : (d.addEventListener("DOMContentLoaded", J), a.addEventListener("load", J))), I.promise(b);
    }, n.ready.promise();
    var K = function (a, b, c, d, e, f, g) {
        var h = 0, i = a.length, j = null == c;
        if ("object" === n.type(c)) {
            e = !0;
            for (h in c) K(a, b, h, c[h], !0, f, g);
        } else if (void 0 !== d && (e = !0, n.isFunction(d) || (g = !0), j && (g ? (b.call(a, d), b = null) : (j = b, b = function (a, b, c) {
            return j.call(n(a), c);
        })), b)) for (; i > h; h++) b(a[h], c, g ? d : d.call(a[h], h, b(a[h], c)));
        return e ? a : j ? b.call(a) : i ? b(a[0], c) : f;
    }, L = function (a) {
        return 1 === a.nodeType || 9 === a.nodeType || !+a.nodeType;
    };

    function M() {
        this.expando = n.expando + M.uid++;
    }

    M.uid = 1, M.prototype = {
        register: function (a, b) {
            var c = b || {};
            return a.nodeType ? a[this.expando] = c : Object.defineProperty(a, this.expando, {
                value: c,
                writable: !0,
                configurable: !0
            }), a[this.expando];
        }, cache: function (a) {
            if (!L(a)) return {};
            var b = a[this.expando];
            return b || (b = {}, L(a) && (a.nodeType ? a[this.expando] = b : Object.defineProperty(a, this.expando, {
                value: b,
                configurable: !0
            }))), b;
        }, set: function (a, b, c) {
            var d, e = this.cache(a);
            if ("string" == typeof b) e[b] = c; else for (d in b) e[d] = b[d];
            return e;
        }, get: function (a, b) {
            return void 0 === b ? this.cache(a) : a[this.expando] && a[this.expando][b];
        }, access: function (a, b, c) {
            var d;
            return void 0 === b || b && "string" == typeof b && void 0 === c ? (d = this.get(a, b), void 0 !== d ? d : this.get(a, n.camelCase(b))) : (this.set(a, b, c), void 0 !== c ? c : b);
        }, remove: function (a, b) {
            var c, d, e, f = a[this.expando];
            if (void 0 !== f) {
                if (void 0 === b) this.register(a); else {
                    n.isArray(b) ? d = b.concat(b.map(n.camelCase)) : (e = n.camelCase(b), b in f ? d = [b, e] : (d = e, d = d in f ? [d] : d.match(G) || [])), c = d.length;
                    while (c--) delete f[d[c]];
                }
                (void 0 === b || n.isEmptyObject(f)) && (a.nodeType ? a[this.expando] = void 0 : delete a[this.expando]);
            }
        }, hasData: function (a) {
            var b = a[this.expando];
            return void 0 !== b && !n.isEmptyObject(b);
        }
    };
    var N = new M, O = new M, P = /^(?:\{[\w\W]*\}|\[[\w\W]*\])$/, Q = /[A-Z]/g;

    function R(a, b, c) {
        var d;
        if (void 0 === c && 1 === a.nodeType) if (d = "data-" + b.replace(Q, "-$&").toLowerCase(), c = a.getAttribute(d), "string" == typeof c) {
            try {
                c = "true" === c ? !0 : "false" === c ? !1 : "null" === c ? null : +c + "" === c ? +c : P.test(c) ? n.parseJSON(c) : c;
            } catch (e) {
            }
            O.set(a, b, c);
        } else c = void 0;
        return c;
    }

    n.extend({
        hasData: function (a) {
            return O.hasData(a) || N.hasData(a);
        }, data: function (a, b, c) {
            return O.access(a, b, c);
        }, removeData: function (a, b) {
            O.remove(a, b);
        }, _data: function (a, b, c) {
            return N.access(a, b, c);
        }, _removeData: function (a, b) {
            N.remove(a, b);
        }
    }), n.fn.extend({
        data: function (a, b) {
            var c, d, e, f = this[0], g = f && f.attributes;
            if (void 0 === a) {
                if (this.length && (e = O.get(f), 1 === f.nodeType && !N.get(f, "hasDataAttrs"))) {
                    c = g.length;
                    while (c--) g[c] && (d = g[c].name, 0 === d.indexOf("data-") && (d = n.camelCase(d.slice(5)), R(f, d, e[d])));
                    N.set(f, "hasDataAttrs", !0);
                }
                return e;
            }
            return "object" == typeof a ? this.each(function () {
                O.set(this, a);
            }) : K(this, function (b) {
                var c, d;
                if (f && void 0 === b) {
                    if (c = O.get(f, a) || O.get(f, a.replace(Q, "-$&").toLowerCase()), void 0 !== c) return c;
                    if (d = n.camelCase(a), c = O.get(f, d), void 0 !== c) return c;
                    if (c = R(f, d, void 0), void 0 !== c) return c;
                } else d = n.camelCase(a), this.each(function () {
                    var c = O.get(this, d);
                    O.set(this, d, b), a.indexOf("-") > -1 && void 0 !== c && O.set(this, a, b);
                });
            }, null, b, arguments.length > 1, null, !0);
        }, removeData: function (a) {
            return this.each(function () {
                O.remove(this, a);
            });
        }
    }), n.extend({
        queue: function (a, b, c) {
            var d;
            return a ? (b = (b || "fx") + "queue", d = N.get(a, b), c && (!d || n.isArray(c) ? d = N.access(a, b, n.makeArray(c)) : d.push(c)), d || []) : void 0;
        }, dequeue: function (a, b) {
            b = b || "fx";
            var c = n.queue(a, b), d = c.length, e = c.shift(), f = n._queueHooks(a, b), g = function () {
                n.dequeue(a, b);
            };
            "inprogress" === e && (e = c.shift(), d--), e && ("fx" === b && c.unshift("inprogress"), delete f.stop, e.call(a, g, f)), !d && f && f.empty.fire();
        }, _queueHooks: function (a, b) {
            var c = b + "queueHooks";
            return N.get(a, c) || N.access(a, c, {
                empty: n.Callbacks("once memory").add(function () {
                    N.remove(a, [b + "queue", c]);
                })
            });
        }
    }), n.fn.extend({
        queue: function (a, b) {
            var c = 2;
            return "string" != typeof a && (b = a, a = "fx", c--), arguments.length < c ? n.queue(this[0], a) : void 0 === b ? this : this.each(function () {
                var c = n.queue(this, a, b);
                n._queueHooks(this, a), "fx" === a && "inprogress" !== c[0] && n.dequeue(this, a);
            });
        }, dequeue: function (a) {
            return this.each(function () {
                n.dequeue(this, a);
            });
        }, clearQueue: function (a) {
            return this.queue(a || "fx", []);
        }, promise: function (a, b) {
            var c, d = 1, e = n.Deferred(), f = this, g = this.length, h = function () {
                --d || e.resolveWith(f, [f]);
            };
            "string" != typeof a && (b = a, a = void 0), a = a || "fx";
            while (g--) c = N.get(f[g], a + "queueHooks"), c && c.empty && (d++, c.empty.add(h));
            return h(), e.promise(b);
        }
    });
    var S = /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source, T = new RegExp("^(?:([+-])=|)(" + S + ")([a-z%]*)$", "i"),
        U = ["Top", "Right", "Bottom", "Left"], V = function (a, b) {
            return a = b || a, "none" === n.css(a, "display") || !n.contains(a.ownerDocument, a);
        };

    function W(a, b, c, d) {
        var e, f = 1, g = 20, h = d ? function () {
                return d.cur();
            } : function () {
                return n.css(a, b, "");
            }, i = h(), j = c && c[3] || (n.cssNumber[b] ? "" : "px"),
            k = (n.cssNumber[b] || "px" !== j && +i) && T.exec(n.css(a, b));
        if (k && k[3] !== j) {
            j = j || k[3], c = c || [], k = +i || 1;
            do f = f || ".5", k /= f, n.style(a, b, k + j); while (f !== (f = h() / i) && 1 !== f && --g);
        }
        return c && (k = +k || +i || 0, e = c[1] ? k + (c[1] + 1) * c[2] : +c[2], d && (d.unit = j, d.start = k, d.end = e)), e;
    }

    var X = /^(?:checkbox|radio)$/i, Y = /<([\w:-]+)/, Z = /^$|\/(?:java|ecma)script/i, $ = {
        option: [1, "<select multiple='multiple'>", "</select>"],
        thead: [1, "<table>", "</table>"],
        col: [2, "<table><colgroup>", "</colgroup></table>"],
        tr: [2, "<table><tbody>", "</tbody></table>"],
        td: [3, "<table><tbody><tr>", "</tr></tbody></table>"],
        _default: [0, "", ""]
    };
    $.optgroup = $.option, $.tbody = $.tfoot = $.colgroup = $.caption = $.thead, $.th = $.td;

    function _(a, b) {
        var c = "undefined" != typeof a.getElementsByTagName ? a.getElementsByTagName(b || "*") : "undefined" != typeof a.querySelectorAll ? a.querySelectorAll(b || "*") : [];
        return void 0 === b || b && n.nodeName(a, b) ? n.merge([a], c) : c;
    }

    function aa(a, b) {
        for (var c = 0, d = a.length; d > c; c++) N.set(a[c], "globalEval", !b || N.get(b[c], "globalEval"));
    }

    var ba = /<|&#?\w+;/;

    function ca(a, b, c, d, e) {
        for (var f, g, h, i, j, k, l = b.createDocumentFragment(), m = [], o = 0, p = a.length; p > o; o++) if (f = a[o], f || 0 === f) if ("object" === n.type(f)) n.merge(m, f.nodeType ? [f] : f); else if (ba.test(f)) {
            g = g || l.appendChild(b.createElement("div")), h = (Y.exec(f) || ["", ""])[1].toLowerCase(), i = $[h] || $._default, g.innerHTML = i[1] + n.htmlPrefilter(f) + i[2], k = i[0];
            while (k--) g = g.lastChild;
            n.merge(m, g.childNodes), g = l.firstChild, g.textContent = "";
        } else m.push(b.createTextNode(f));
        l.textContent = "", o = 0;
        while (f = m[o++]) if (d && n.inArray(f, d) > -1) e && e.push(f); else if (j = n.contains(f.ownerDocument, f), g = _(l.appendChild(f), "script"), j && aa(g), c) {
            k = 0;
            while (f = g[k++]) Z.test(f.type || "") && c.push(f);
        }
        return l;
    }

    !function () {
        var a = d.createDocumentFragment(), b = a.appendChild(d.createElement("div")), c = d.createElement("input");
        c.setAttribute("type", "radio"), c.setAttribute("checked", "checked"), c.setAttribute("name", "t"), b.appendChild(c), l.checkClone = b.cloneNode(!0).cloneNode(!0).lastChild.checked, b.innerHTML = "<textarea>x</textarea>", l.noCloneChecked = !!b.cloneNode(!0).lastChild.defaultValue;
    }();
    var da = /^key/, ea = /^(?:mouse|pointer|contextmenu|drag|drop)|click/, fa = /^([^.]*)(?:\.(.+)|)/;

    function ga() {
        return !0;
    }

    function ha() {
        return !1;
    }

    function ia() {
        try {
            return d.activeElement;
        } catch (a) {
        }
    }

    function ja(a, b, c, d, e, f) {
        var g, h;
        if ("object" == typeof b) {
            "string" != typeof c && (d = d || c, c = void 0);
            for (h in b) ja(a, h, c, d, b[h], f);
            return a;
        }
        if (null == d && null == e ? (e = c, d = c = void 0) : null == e && ("string" == typeof c ? (e = d, d = void 0) : (e = d, d = c, c = void 0)), e === !1) e = ha; else if (!e) return a;
        return 1 === f && (g = e, e = function (a) {
            return n().off(a), g.apply(this, arguments);
        }, e.guid = g.guid || (g.guid = n.guid++)), a.each(function () {
            n.event.add(this, b, e, d, c);
        });
    }

    n.event = {
        global: {},
        add: function (a, b, c, d, e) {
            var f, g, h, i, j, k, l, m, o, p, q, r = N.get(a);
            if (r) {
                c.handler && (f = c, c = f.handler, e = f.selector), c.guid || (c.guid = n.guid++), (i = r.events) || (i = r.events = {}), (g = r.handle) || (g = r.handle = function (b) {
                    return "undefined" != typeof n && n.event.triggered !== b.type ? n.event.dispatch.apply(a, arguments) : void 0;
                }), b = (b || "").match(G) || [""], j = b.length;
                while (j--) h = fa.exec(b[j]) || [], o = q = h[1], p = (h[2] || "").split(".").sort(), o && (l = n.event.special[o] || {}, o = (e ? l.delegateType : l.bindType) || o, l = n.event.special[o] || {}, k = n.extend({
                    type: o,
                    origType: q,
                    data: d,
                    handler: c,
                    guid: c.guid,
                    selector: e,
                    needsContext: e && n.expr.match.needsContext.test(e),
                    namespace: p.join(".")
                }, f), (m = i[o]) || (m = i[o] = [], m.delegateCount = 0, l.setup && l.setup.call(a, d, p, g) !== !1 || a.addEventListener && a.addEventListener(o, g)), l.add && (l.add.call(a, k), k.handler.guid || (k.handler.guid = c.guid)), e ? m.splice(m.delegateCount++, 0, k) : m.push(k), n.event.global[o] = !0);
            }
        },
        remove: function (a, b, c, d, e) {
            var f, g, h, i, j, k, l, m, o, p, q, r = N.hasData(a) && N.get(a);
            if (r && (i = r.events)) {
                b = (b || "").match(G) || [""], j = b.length;
                while (j--) if (h = fa.exec(b[j]) || [], o = q = h[1], p = (h[2] || "").split(".").sort(), o) {
                    l = n.event.special[o] || {}, o = (d ? l.delegateType : l.bindType) || o, m = i[o] || [], h = h[2] && new RegExp("(^|\\.)" + p.join("\\.(?:.*\\.|)") + "(\\.|$)"), g = f = m.length;
                    while (f--) k = m[f], !e && q !== k.origType || c && c.guid !== k.guid || h && !h.test(k.namespace) || d && d !== k.selector && ("**" !== d || !k.selector) || (m.splice(f, 1), k.selector && m.delegateCount--, l.remove && l.remove.call(a, k));
                    g && !m.length && (l.teardown && l.teardown.call(a, p, r.handle) !== !1 || n.removeEvent(a, o, r.handle), delete i[o]);
                } else for (o in i) n.event.remove(a, o + b[j], c, d, !0);
                n.isEmptyObject(i) && N.remove(a, "handle events");
            }
        },
        dispatch: function (a) {
            a = n.event.fix(a);
            var b, c, d, f, g, h = [], i = e.call(arguments), j = (N.get(this, "events") || {})[a.type] || [],
                k = n.event.special[a.type] || {};
            if (i[0] = a, a.delegateTarget = this, !k.preDispatch || k.preDispatch.call(this, a) !== !1) {
                h = n.event.handlers.call(this, a, j), b = 0;
                while ((f = h[b++]) && !a.isPropagationStopped()) {
                    a.currentTarget = f.elem, c = 0;
                    while ((g = f.handlers[c++]) && !a.isImmediatePropagationStopped()) a.rnamespace && !a.rnamespace.test(g.namespace) || (a.handleObj = g, a.data = g.data, d = ((n.event.special[g.origType] || {}).handle || g.handler).apply(f.elem, i), void 0 !== d && (a.result = d) === !1 && (a.preventDefault(), a.stopPropagation()));
                }
                return k.postDispatch && k.postDispatch.call(this, a), a.result;
            }
        },
        handlers: function (a, b) {
            var c, d, e, f, g = [], h = b.delegateCount, i = a.target;
            if (h && i.nodeType && ("click" !== a.type || isNaN(a.button) || a.button < 1)) for (; i !== this; i = i.parentNode || this) if (1 === i.nodeType && (i.disabled !== !0 || "click" !== a.type)) {
                for (d = [], c = 0; h > c; c++) f = b[c], e = f.selector + " ", void 0 === d[e] && (d[e] = f.needsContext ? n(e, this).index(i) > -1 : n.find(e, this, null, [i]).length), d[e] && d.push(f);
                d.length && g.push({elem: i, handlers: d});
            }
            return h < b.length && g.push({elem: this, handlers: b.slice(h)}), g;
        },
        props: "altKey bubbles cancelable ctrlKey currentTarget detail eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),
        fixHooks: {},
        keyHooks: {
            props: "char charCode key keyCode".split(" "), filter: function (a, b) {
                return null == a.which && (a.which = null != b.charCode ? b.charCode : b.keyCode), a;
            }
        },
        mouseHooks: {
            props: "button buttons clientX clientY offsetX offsetY pageX pageY screenX screenY toElement".split(" "),
            filter: function (a, b) {
                var c, e, f, g = b.button;
                return null == a.pageX && null != b.clientX && (c = a.target.ownerDocument || d, e = c.documentElement, f = c.body, a.pageX = b.clientX + (e && e.scrollLeft || f && f.scrollLeft || 0) - (e && e.clientLeft || f && f.clientLeft || 0), a.pageY = b.clientY + (e && e.scrollTop || f && f.scrollTop || 0) - (e && e.clientTop || f && f.clientTop || 0)), a.which || void 0 === g || (a.which = 1 & g ? 1 : 2 & g ? 3 : 4 & g ? 2 : 0), a;
            }
        },
        fix: function (a) {
            if (a[n.expando]) return a;
            var b, c, e, f = a.type, g = a, h = this.fixHooks[f];
            h || (this.fixHooks[f] = h = ea.test(f) ? this.mouseHooks : da.test(f) ? this.keyHooks : {}), e = h.props ? this.props.concat(h.props) : this.props, a = new n.Event(g), b = e.length;
            while (b--) c = e[b], a[c] = g[c];
            return a.target || (a.target = d), 3 === a.target.nodeType && (a.target = a.target.parentNode), h.filter ? h.filter(a, g) : a;
        },
        special: {
            load: {noBubble: !0}, focus: {
                trigger: function () {
                    return this !== ia() && this.focus ? (this.focus(), !1) : void 0;
                }, delegateType: "focusin"
            }, blur: {
                trigger: function () {
                    return this === ia() && this.blur ? (this.blur(), !1) : void 0;
                }, delegateType: "focusout"
            }, click: {
                trigger: function () {
                    return "checkbox" === this.type && this.click && n.nodeName(this, "input") ? (this.click(), !1) : void 0;
                }, _default: function (a) {
                    return n.nodeName(a.target, "a");
                }
            }, beforeunload: {
                postDispatch: function (a) {
                    void 0 !== a.result && a.originalEvent && (a.originalEvent.returnValue = a.result);
                }
            }
        }
    }, n.removeEvent = function (a, b, c) {
        a.removeEventListener && a.removeEventListener(b, c);
    }, n.Event = function (a, b) {
        return this instanceof n.Event ? (a && a.type ? (this.originalEvent = a, this.type = a.type, this.isDefaultPrevented = a.defaultPrevented || void 0 === a.defaultPrevented && a.returnValue === !1 ? ga : ha) : this.type = a, b && n.extend(this, b), this.timeStamp = a && a.timeStamp || n.now(), void(this[n.expando] = !0)) : new n.Event(a, b);
    }, n.Event.prototype = {
        constructor: n.Event,
        isDefaultPrevented: ha,
        isPropagationStopped: ha,
        isImmediatePropagationStopped: ha,
        isSimulated: !1,
        preventDefault: function () {
            var a = this.originalEvent;
            this.isDefaultPrevented = ga, a && !this.isSimulated && a.preventDefault();
        },
        stopPropagation: function () {
            var a = this.originalEvent;
            this.isPropagationStopped = ga, a && !this.isSimulated && a.stopPropagation();
        },
        stopImmediatePropagation: function () {
            var a = this.originalEvent;
            this.isImmediatePropagationStopped = ga, a && !this.isSimulated && a.stopImmediatePropagation(), this.stopPropagation();
        }
    }, n.each({
        mouseenter: "mouseover",
        mouseleave: "mouseout",
        pointerenter: "pointerover",
        pointerleave: "pointerout"
    }, function (a, b) {
        n.event.special[a] = {
            delegateType: b, bindType: b, handle: function (a) {
                var c, d = this, e = a.relatedTarget, f = a.handleObj;
                return e && (e === d || n.contains(d, e)) || (a.type = f.origType, c = f.handler.apply(this, arguments), a.type = b), c;
            }
        };
    }), n.fn.extend({
        on: function (a, b, c, d) {
            return ja(this, a, b, c, d);
        }, one: function (a, b, c, d) {
            return ja(this, a, b, c, d, 1);
        }, off: function (a, b, c) {
            var d, e;
            if (a && a.preventDefault && a.handleObj) return d = a.handleObj, n(a.delegateTarget).off(d.namespace ? d.origType + "." + d.namespace : d.origType, d.selector, d.handler), this;
            if ("object" == typeof a) {
                for (e in a) this.off(e, b, a[e]);
                return this;
            }
            return b !== !1 && "function" != typeof b || (c = b, b = void 0), c === !1 && (c = ha), this.each(function () {
                n.event.remove(this, a, c, b);
            });
        }
    });
    var ka = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:-]+)[^>]*)\/>/gi, la = /<script|<style|<link/i,
        ma = /checked\s*(?:[^=]|=\s*.checked.)/i, na = /^true\/(.*)/, oa = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g;

    function pa(a, b) {
        return n.nodeName(a, "table") && n.nodeName(11 !== b.nodeType ? b : b.firstChild, "tr") ? a.getElementsByTagName("tbody")[0] || a.appendChild(a.ownerDocument.createElement("tbody")) : a;
    }

    function qa(a) {
        return a.type = (null !== a.getAttribute("type")) + "/" + a.type, a;
    }

    function ra(a) {
        var b = na.exec(a.type);
        return b ? a.type = b[1] : a.removeAttribute("type"), a;
    }

    function sa(a, b) {
        var c, d, e, f, g, h, i, j;
        if (1 === b.nodeType) {
            if (N.hasData(a) && (f = N.access(a), g = N.set(b, f), j = f.events)) {
                delete g.handle, g.events = {};
                for (e in j) for (c = 0, d = j[e].length; d > c; c++) n.event.add(b, e, j[e][c]);
            }
            O.hasData(a) && (h = O.access(a), i = n.extend({}, h), O.set(b, i));
        }
    }

    function ta(a, b) {
        var c = b.nodeName.toLowerCase();
        "input" === c && X.test(a.type) ? b.checked = a.checked : "input" !== c && "textarea" !== c || (b.defaultValue = a.defaultValue);
    }

    function ua(a, b, c, d) {
        b = f.apply([], b);
        var e, g, h, i, j, k, m = 0, o = a.length, p = o - 1, q = b[0], r = n.isFunction(q);
        if (r || o > 1 && "string" == typeof q && !l.checkClone && ma.test(q)) return a.each(function (e) {
            var f = a.eq(e);
            r && (b[0] = q.call(this, e, f.html())), ua(f, b, c, d);
        });
        if (o && (e = ca(b, a[0].ownerDocument, !1, a, d), g = e.firstChild, 1 === e.childNodes.length && (e = g), g || d)) {
            for (h = n.map(_(e, "script"), qa), i = h.length; o > m; m++) j = e, m !== p && (j = n.clone(j, !0, !0), i && n.merge(h, _(j, "script"))), c.call(a[m], j, m);
            if (i) for (k = h[h.length - 1].ownerDocument, n.map(h, ra), m = 0; i > m; m++) j = h[m], Z.test(j.type || "") && !N.access(j, "globalEval") && n.contains(k, j) && (j.src ? n._evalUrl && n._evalUrl(j.src) : n.globalEval(j.textContent.replace(oa, "")));
        }
        return a;
    }

    function va(a, b, c) {
        for (var d, e = b ? n.filter(b, a) : a, f = 0; null != (d = e[f]); f++) c || 1 !== d.nodeType || n.cleanData(_(d)), d.parentNode && (c && n.contains(d.ownerDocument, d) && aa(_(d, "script")), d.parentNode.removeChild(d));
        return a;
    }

    n.extend({
        htmlPrefilter: function (a) {
            return a.replace(ka, "<$1></$2>");
        }, clone: function (a, b, c) {
            var d, e, f, g, h = a.cloneNode(!0), i = n.contains(a.ownerDocument, a);
            if (!(l.noCloneChecked || 1 !== a.nodeType && 11 !== a.nodeType || n.isXMLDoc(a))) for (g = _(h), f = _(a), d = 0, e = f.length; e > d; d++) ta(f[d], g[d]);
            if (b) if (c) for (f = f || _(a), g = g || _(h), d = 0, e = f.length; e > d; d++) sa(f[d], g[d]); else sa(a, h);
            return g = _(h, "script"), g.length > 0 && aa(g, !i && _(a, "script")), h;
        }, cleanData: function (a) {
            for (var b, c, d, e = n.event.special, f = 0; void 0 !== (c = a[f]); f++) if (L(c)) {
                if (b = c[N.expando]) {
                    if (b.events) for (d in b.events) e[d] ? n.event.remove(c, d) : n.removeEvent(c, d, b.handle);
                    c[N.expando] = void 0;
                }
                c[O.expando] && (c[O.expando] = void 0);
            }
        }
    }), n.fn.extend({
        domManip: ua, detach: function (a) {
            return va(this, a, !0);
        }, remove: function (a) {
            return va(this, a);
        }, text: function (a) {
            return K(this, function (a) {
                return void 0 === a ? n.text(this) : this.empty().each(function () {
                    1 !== this.nodeType && 11 !== this.nodeType && 9 !== this.nodeType || (this.textContent = a);
                });
            }, null, a, arguments.length);
        }, append: function () {
            return ua(this, arguments, function (a) {
                if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                    var b = pa(this, a);
                    b.appendChild(a);
                }
            });
        }, prepend: function () {
            return ua(this, arguments, function (a) {
                if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                    var b = pa(this, a);
                    b.insertBefore(a, b.firstChild);
                }
            });
        }, before: function () {
            return ua(this, arguments, function (a) {
                this.parentNode && this.parentNode.insertBefore(a, this);
            });
        }, after: function () {
            return ua(this, arguments, function (a) {
                this.parentNode && this.parentNode.insertBefore(a, this.nextSibling);
            });
        }, empty: function () {
            for (var a, b = 0; null != (a = this[b]); b++) 1 === a.nodeType && (n.cleanData(_(a, !1)), a.textContent = "");
            return this;
        }, clone: function (a, b) {
            return a = null == a ? !1 : a, b = null == b ? a : b, this.map(function () {
                return n.clone(this, a, b);
            });
        }, html: function (a) {
            return K(this, function (a) {
                var b = this[0] || {}, c = 0, d = this.length;
                if (void 0 === a && 1 === b.nodeType) return b.innerHTML;
                if ("string" == typeof a && !la.test(a) && !$[(Y.exec(a) || ["", ""])[1].toLowerCase()]) {
                    a = n.htmlPrefilter(a);
                    try {
                        for (; d > c; c++) b = this[c] || {}, 1 === b.nodeType && (n.cleanData(_(b, !1)), b.innerHTML = a);
                        b = 0;
                    } catch (e) {
                    }
                }
                b && this.empty().append(a);
            }, null, a, arguments.length);
        }, replaceWith: function () {
            var a = [];
            return ua(this, arguments, function (b) {
                var c = this.parentNode;
                n.inArray(this, a) < 0 && (n.cleanData(_(this)), c && c.replaceChild(b, this));
            }, a);
        }
    }), n.each({
        appendTo: "append",
        prependTo: "prepend",
        insertBefore: "before",
        insertAfter: "after",
        replaceAll: "replaceWith"
    }, function (a, b) {
        n.fn[a] = function (a) {
            for (var c, d = [], e = n(a), f = e.length - 1, h = 0; f >= h; h++) c = h === f ? this : this.clone(!0), n(e[h])[b](c), g.apply(d, c.get());
            return this.pushStack(d);
        };
    });
    var wa, xa = {HTML: "block", BODY: "block"};

    function ya(a, b) {
        var c = n(b.createElement(a)).appendTo(b.body), d = n.css(c[0], "display");
        return c.detach(), d;
    }

    function za(a) {
        var b = d, c = xa[a];
        return c || (c = ya(a, b), "none" !== c && c || (wa = (wa || n("<iframe frameborder='0' width='0' height='0'/>")).appendTo(b.documentElement), b = wa[0].contentDocument, b.write(), b.close(), c = ya(a, b), wa.detach()), xa[a] = c), c;
    }

    var Aa = /^margin/, Ba = new RegExp("^(" + S + ")(?!px)[a-z%]+$", "i"), Ca = function (b) {
        var c = b.ownerDocument.defaultView;
        return c && c.opener || (c = a), c.getComputedStyle(b);
    }, Da = function (a, b, c, d) {
        var e, f, g = {};
        for (f in b) g[f] = a.style[f], a.style[f] = b[f];
        e = c.apply(a, d || []);
        for (f in b) a.style[f] = g[f];
        return e;
    }, Ea = d.documentElement;
    !function () {
        var b, c, e, f, g = d.createElement("div"), h = d.createElement("div");
        if (h.style) {
            h.style.backgroundClip = "content-box", h.cloneNode(!0).style.backgroundClip = "", l.clearCloneStyle = "content-box" === h.style.backgroundClip, g.style.cssText = "border:0;width:8px;height:0;top:0;left:-9999px;padding:0;margin-top:1px;position:absolute", g.appendChild(h);

            function i() {
                h.style.cssText = "-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;position:relative;display:block;margin:auto;border:1px;padding:1px;top:1%;width:50%", h.innerHTML = "", Ea.appendChild(g);
                var d = a.getComputedStyle(h);
                b = "1%" !== d.top, f = "2px" === d.marginLeft, c = "4px" === d.width, h.style.marginRight = "50%", e = "4px" === d.marginRight, Ea.removeChild(g);
            }

            n.extend(l, {
                pixelPosition: function () {
                    return i(), b;
                }, boxSizingReliable: function () {
                    return null == c && i(), c;
                }, pixelMarginRight: function () {
                    return null == c && i(), e;
                }, reliableMarginLeft: function () {
                    return null == c && i(), f;
                }, reliableMarginRight: function () {
                    var b, c = h.appendChild(d.createElement("div"));
                    return c.style.cssText = h.style.cssText = "-webkit-box-sizing:content-box;box-sizing:content-box;display:block;margin:0;border:0;padding:0", c.style.marginRight = c.style.width = "0", h.style.width = "1px", Ea.appendChild(g), b = !parseFloat(a.getComputedStyle(c).marginRight), Ea.removeChild(g), h.removeChild(c), b;
                }
            });
        }
    }();

    function Fa(a, b, c) {
        var d, e, f, g, h = a.style;
        return c = c || Ca(a), g = c ? c.getPropertyValue(b) || c[b] : void 0, "" !== g && void 0 !== g || n.contains(a.ownerDocument, a) || (g = n.style(a, b)), c && !l.pixelMarginRight() && Ba.test(g) && Aa.test(b) && (d = h.width, e = h.minWidth, f = h.maxWidth, h.minWidth = h.maxWidth = h.width = g, g = c.width, h.width = d, h.minWidth = e, h.maxWidth = f), void 0 !== g ? g + "" : g;
    }

    function Ga(a, b) {
        return {
            get: function () {
                return a() ? void delete this.get : (this.get = b).apply(this, arguments);
            }
        };
    }

    var Ha = /^(none|table(?!-c[ea]).+)/, Ia = {position: "absolute", visibility: "hidden", display: "block"},
        Ja = {letterSpacing: "0", fontWeight: "400"}, Ka = ["Webkit", "O", "Moz", "ms"],
        La = d.createElement("div").style;

    function Ma(a) {
        if (a in La) return a;
        var b = a[0].toUpperCase() + a.slice(1), c = Ka.length;
        while (c--) if (a = Ka[c] + b, a in La) return a;
    }

    function Na(a, b, c) {
        var d = T.exec(b);
        return d ? Math.max(0, d[2] - (c || 0)) + (d[3] || "px") : b;
    }

    function Oa(a, b, c, d, e) {
        for (var f = c === (d ? "border" : "content") ? 4 : "width" === b ? 1 : 0, g = 0; 4 > f; f += 2) "margin" === c && (g += n.css(a, c + U[f], !0, e)), d ? ("content" === c && (g -= n.css(a, "padding" + U[f], !0, e)), "margin" !== c && (g -= n.css(a, "border" + U[f] + "Width", !0, e))) : (g += n.css(a, "padding" + U[f], !0, e), "padding" !== c && (g += n.css(a, "border" + U[f] + "Width", !0, e)));
        return g;
    }

    function Pa(a, b, c) {
        var d = !0, e = "width" === b ? a.offsetWidth : a.offsetHeight, f = Ca(a),
            g = "border-box" === n.css(a, "boxSizing", !1, f);
        if (0 >= e || null == e) {
            if (e = Fa(a, b, f), (0 > e || null == e) && (e = a.style[b]), Ba.test(e)) return e;
            d = g && (l.boxSizingReliable() || e === a.style[b]), e = parseFloat(e) || 0;
        }
        return e + Oa(a, b, c || (g ? "border" : "content"), d, f) + "px";
    }

    function Qa(a, b) {
        for (var c, d, e, f = [], g = 0, h = a.length; h > g; g++) d = a[g], d.style && (f[g] = N.get(d, "olddisplay"), c = d.style.display, b ? (f[g] || "none" !== c || (d.style.display = ""), "" === d.style.display && V(d) && (f[g] = N.access(d, "olddisplay", za(d.nodeName)))) : (e = V(d), "none" === c && e || N.set(d, "olddisplay", e ? c : n.css(d, "display"))));
        for (g = 0; h > g; g++) d = a[g], d.style && (b && "none" !== d.style.display && "" !== d.style.display || (d.style.display = b ? f[g] || "" : "none"));
        return a;
    }

    n.extend({
        cssHooks: {
            opacity: {
                get: function (a, b) {
                    if (b) {
                        var c = Fa(a, "opacity");
                        return "" === c ? "1" : c;
                    }
                }
            }
        },
        cssNumber: {
            animationIterationCount: !0,
            columnCount: !0,
            fillOpacity: !0,
            flexGrow: !0,
            flexShrink: !0,
            fontWeight: !0,
            lineHeight: !0,
            opacity: !0,
            order: !0,
            orphans: !0,
            widows: !0,
            zIndex: !0,
            zoom: !0
        },
        cssProps: {"float": "cssFloat"},
        style: function (a, b, c, d) {
            if (a && 3 !== a.nodeType && 8 !== a.nodeType && a.style) {
                var e, f, g, h = n.camelCase(b), i = a.style;
                return b = n.cssProps[h] || (n.cssProps[h] = Ma(h) || h), g = n.cssHooks[b] || n.cssHooks[h], void 0 === c ? g && "get" in g && void 0 !== (e = g.get(a, !1, d)) ? e : i[b] : (f = typeof c, "string" === f && (e = T.exec(c)) && e[1] && (c = W(a, b, e), f = "number"), null != c && c === c && ("number" === f && (c += e && e[3] || (n.cssNumber[h] ? "" : "px")), l.clearCloneStyle || "" !== c || 0 !== b.indexOf("background") || (i[b] = "inherit"), g && "set" in g && void 0 === (c = g.set(a, c, d)) || (i[b] = c)), void 0);
            }
        },
        css: function (a, b, c, d) {
            var e, f, g, h = n.camelCase(b);
            return b = n.cssProps[h] || (n.cssProps[h] = Ma(h) || h), g = n.cssHooks[b] || n.cssHooks[h], g && "get" in g && (e = g.get(a, !0, c)), void 0 === e && (e = Fa(a, b, d)), "normal" === e && b in Ja && (e = Ja[b]), "" === c || c ? (f = parseFloat(e), c === !0 || isFinite(f) ? f || 0 : e) : e;
        }
    }), n.each(["height", "width"], function (a, b) {
        n.cssHooks[b] = {
            get: function (a, c, d) {
                return c ? Ha.test(n.css(a, "display")) && 0 === a.offsetWidth ? Da(a, Ia, function () {
                    return Pa(a, b, d);
                }) : Pa(a, b, d) : void 0;
            }, set: function (a, c, d) {
                var e, f = d && Ca(a), g = d && Oa(a, b, d, "border-box" === n.css(a, "boxSizing", !1, f), f);
                return g && (e = T.exec(c)) && "px" !== (e[3] || "px") && (a.style[b] = c, c = n.css(a, b)), Na(a, c, g);
            }
        };
    }), n.cssHooks.marginLeft = Ga(l.reliableMarginLeft, function (a, b) {
        return b ? (parseFloat(Fa(a, "marginLeft")) || a.getBoundingClientRect().left - Da(a, {marginLeft: 0}, function () {
            return a.getBoundingClientRect().left;
        })) + "px" : void 0;
    }), n.cssHooks.marginRight = Ga(l.reliableMarginRight, function (a, b) {
        return b ? Da(a, {display: "inline-block"}, Fa, [a, "marginRight"]) : void 0;
    }), n.each({margin: "", padding: "", border: "Width"}, function (a, b) {
        n.cssHooks[a + b] = {
            expand: function (c) {
                for (var d = 0, e = {}, f = "string" == typeof c ? c.split(" ") : [c]; 4 > d; d++) e[a + U[d] + b] = f[d] || f[d - 2] || f[0];
                return e;
            }
        }, Aa.test(a) || (n.cssHooks[a + b].set = Na);
    }), n.fn.extend({
        css: function (a, b) {
            return K(this, function (a, b, c) {
                var d, e, f = {}, g = 0;
                if (n.isArray(b)) {
                    for (d = Ca(a), e = b.length; e > g; g++) f[b[g]] = n.css(a, b[g], !1, d);
                    return f;
                }
                return void 0 !== c ? n.style(a, b, c) : n.css(a, b);
            }, a, b, arguments.length > 1);
        }, show: function () {
            return Qa(this, !0);
        }, hide: function () {
            return Qa(this);
        }, toggle: function (a) {
            return "boolean" == typeof a ? a ? this.show() : this.hide() : this.each(function () {
                V(this) ? n(this).show() : n(this).hide();
            });
        }
    });

    function Ra(a, b, c, d, e) {
        return new Ra.prototype.init(a, b, c, d, e);
    }

    n.Tween = Ra, Ra.prototype = {
        constructor: Ra, init: function (a, b, c, d, e, f) {
            this.elem = a, this.prop = c, this.easing = e || n.easing._default, this.options = b, this.start = this.now = this.cur(), this.end = d, this.unit = f || (n.cssNumber[c] ? "" : "px");
        }, cur: function () {
            var a = Ra.propHooks[this.prop];
            return a && a.get ? a.get(this) : Ra.propHooks._default.get(this);
        }, run: function (a) {
            var b, c = Ra.propHooks[this.prop];
            return this.options.duration ? this.pos = b = n.easing[this.easing](a, this.options.duration * a, 0, 1, this.options.duration) : this.pos = b = a, this.now = (this.end - this.start) * b + this.start, this.options.step && this.options.step.call(this.elem, this.now, this), c && c.set ? c.set(this) : Ra.propHooks._default.set(this), this;
        }
    }, Ra.prototype.init.prototype = Ra.prototype, Ra.propHooks = {
        _default: {
            get: function (a) {
                var b;
                return 1 !== a.elem.nodeType || null != a.elem[a.prop] && null == a.elem.style[a.prop] ? a.elem[a.prop] : (b = n.css(a.elem, a.prop, ""), b && "auto" !== b ? b : 0);
            }, set: function (a) {
                n.fx.step[a.prop] ? n.fx.step[a.prop](a) : 1 !== a.elem.nodeType || null == a.elem.style[n.cssProps[a.prop]] && !n.cssHooks[a.prop] ? a.elem[a.prop] = a.now : n.style(a.elem, a.prop, a.now + a.unit);
            }
        }
    }, Ra.propHooks.scrollTop = Ra.propHooks.scrollLeft = {
        set: function (a) {
            a.elem.nodeType && a.elem.parentNode && (a.elem[a.prop] = a.now);
        }
    }, n.easing = {
        linear: function (a) {
            return a;
        }, swing: function (a) {
            return .5 - Math.cos(a * Math.PI) / 2;
        }, _default: "swing"
    }, n.fx = Ra.prototype.init, n.fx.step = {};
    var Sa, Ta, Ua = /^(?:toggle|show|hide)$/, Va = /queueHooks$/;

    function Wa() {
        return a.setTimeout(function () {
            Sa = void 0;
        }), Sa = n.now();
    }

    function Xa(a, b) {
        var c, d = 0, e = {height: a};
        for (b = b ? 1 : 0; 4 > d; d += 2 - b) c = U[d], e["margin" + c] = e["padding" + c] = a;
        return b && (e.opacity = e.width = a), e;
    }

    function Ya(a, b, c) {
        for (var d, e = (_a.tweeners[b] || []).concat(_a.tweeners["*"]), f = 0, g = e.length; g > f; f++) if (d = e[f].call(c, b, a)) return d;
    }

    function Za(a, b, c) {
        var d, e, f, g, h, i, j, k, l = this, m = {}, o = a.style, p = a.nodeType && V(a), q = N.get(a, "fxshow");
        c.queue || (h = n._queueHooks(a, "fx"), null == h.unqueued && (h.unqueued = 0, i = h.empty.fire, h.empty.fire = function () {
            h.unqueued || i();
        }), h.unqueued++, l.always(function () {
            l.always(function () {
                h.unqueued--, n.queue(a, "fx").length || h.empty.fire();
            });
        })), 1 === a.nodeType && ("height" in b || "width" in b) && (c.overflow = [o.overflow, o.overflowX, o.overflowY], j = n.css(a, "display"), k = "none" === j ? N.get(a, "olddisplay") || za(a.nodeName) : j, "inline" === k && "none" === n.css(a, "float") && (o.display = "inline-block")), c.overflow && (o.overflow = "hidden", l.always(function () {
            o.overflow = c.overflow[0], o.overflowX = c.overflow[1], o.overflowY = c.overflow[2];
        }));
        for (d in b) if (e = b[d], Ua.exec(e)) {
            if (delete b[d], f = f || "toggle" === e, e === (p ? "hide" : "show")) {
                if ("show" !== e || !q || void 0 === q[d]) continue;
                p = !0;
            }
            m[d] = q && q[d] || n.style(a, d);
        } else j = void 0;
        if (n.isEmptyObject(m)) "inline" === ("none" === j ? za(a.nodeName) : j) && (o.display = j); else {
            q ? "hidden" in q && (p = q.hidden) : q = N.access(a, "fxshow", {}), f && (q.hidden = !p), p ? n(a).show() : l.done(function () {
                n(a).hide();
            }), l.done(function () {
                var b;
                N.remove(a, "fxshow");
                for (b in m) n.style(a, b, m[b]);
            });
            for (d in m) g = Ya(p ? q[d] : 0, d, l), d in q || (q[d] = g.start, p && (g.end = g.start, g.start = "width" === d || "height" === d ? 1 : 0));
        }
    }

    function $a(a, b) {
        var c, d, e, f, g;
        for (c in a) if (d = n.camelCase(c), e = b[d], f = a[c], n.isArray(f) && (e = f[1], f = a[c] = f[0]), c !== d && (a[d] = f, delete a[c]), g = n.cssHooks[d], g && "expand" in g) {
            f = g.expand(f), delete a[d];
            for (c in f) c in a || (a[c] = f[c], b[c] = e);
        } else b[d] = e;
    }

    function _a(a, b, c) {
        var d, e, f = 0, g = _a.prefilters.length, h = n.Deferred().always(function () {
            delete i.elem;
        }), i = function () {
            if (e) return !1;
            for (var b = Sa || Wa(), c = Math.max(0, j.startTime + j.duration - b), d = c / j.duration || 0, f = 1 - d, g = 0, i = j.tweens.length; i > g; g++) j.tweens[g].run(f);
            return h.notifyWith(a, [j, f, c]), 1 > f && i ? c : (h.resolveWith(a, [j]), !1);
        }, j = h.promise({
            elem: a,
            props: n.extend({}, b),
            opts: n.extend(!0, {specialEasing: {}, easing: n.easing._default}, c),
            originalProperties: b,
            originalOptions: c,
            startTime: Sa || Wa(),
            duration: c.duration,
            tweens: [],
            createTween: function (b, c) {
                var d = n.Tween(a, j.opts, b, c, j.opts.specialEasing[b] || j.opts.easing);
                return j.tweens.push(d), d;
            },
            stop: function (b) {
                var c = 0, d = b ? j.tweens.length : 0;
                if (e) return this;
                for (e = !0; d > c; c++) j.tweens[c].run(1);
                return b ? (h.notifyWith(a, [j, 1, 0]), h.resolveWith(a, [j, b])) : h.rejectWith(a, [j, b]), this;
            }
        }), k = j.props;
        for ($a(k, j.opts.specialEasing); g > f; f++) if (d = _a.prefilters[f].call(j, a, k, j.opts)) return n.isFunction(d.stop) && (n._queueHooks(j.elem, j.opts.queue).stop = n.proxy(d.stop, d)), d;
        return n.map(k, Ya, j), n.isFunction(j.opts.start) && j.opts.start.call(a, j), n.fx.timer(n.extend(i, {
            elem: a,
            anim: j,
            queue: j.opts.queue
        })), j.progress(j.opts.progress).done(j.opts.done, j.opts.complete).fail(j.opts.fail).always(j.opts.always);
    }

    n.Animation = n.extend(_a, {
        tweeners: {
            "*": [function (a, b) {
                var c = this.createTween(a, b);
                return W(c.elem, a, T.exec(b), c), c;
            }]
        }, tweener: function (a, b) {
            n.isFunction(a) ? (b = a, a = ["*"]) : a = a.match(G);
            for (var c, d = 0, e = a.length; e > d; d++) c = a[d], _a.tweeners[c] = _a.tweeners[c] || [], _a.tweeners[c].unshift(b);
        }, prefilters: [Za], prefilter: function (a, b) {
            b ? _a.prefilters.unshift(a) : _a.prefilters.push(a);
        }
    }), n.speed = function (a, b, c) {
        var d = a && "object" == typeof a ? n.extend({}, a) : {
            complete: c || !c && b || n.isFunction(a) && a,
            duration: a,
            easing: c && b || b && !n.isFunction(b) && b
        };
        return d.duration = n.fx.off ? 0 : "number" == typeof d.duration ? d.duration : d.duration in n.fx.speeds ? n.fx.speeds[d.duration] : n.fx.speeds._default, null != d.queue && d.queue !== !0 || (d.queue = "fx"), d.old = d.complete, d.complete = function () {
            n.isFunction(d.old) && d.old.call(this), d.queue && n.dequeue(this, d.queue);
        }, d;
    }, n.fn.extend({
        fadeTo: function (a, b, c, d) {
            return this.filter(V).css("opacity", 0).show().end().animate({opacity: b}, a, c, d);
        }, animate: function (a, b, c, d) {
            var e = n.isEmptyObject(a), f = n.speed(b, c, d), g = function () {
                var b = _a(this, n.extend({}, a), f);
                (e || N.get(this, "finish")) && b.stop(!0);
            };
            return g.finish = g, e || f.queue === !1 ? this.each(g) : this.queue(f.queue, g);
        }, stop: function (a, b, c) {
            var d = function (a) {
                var b = a.stop;
                delete a.stop, b(c);
            };
            return "string" != typeof a && (c = b, b = a, a = void 0), b && a !== !1 && this.queue(a || "fx", []), this.each(function () {
                var b = !0, e = null != a && a + "queueHooks", f = n.timers, g = N.get(this);
                if (e) g[e] && g[e].stop && d(g[e]); else for (e in g) g[e] && g[e].stop && Va.test(e) && d(g[e]);
                for (e = f.length; e--;) f[e].elem !== this || null != a && f[e].queue !== a || (f[e].anim.stop(c), b = !1, f.splice(e, 1));
                !b && c || n.dequeue(this, a);
            });
        }, finish: function (a) {
            return a !== !1 && (a = a || "fx"), this.each(function () {
                var b, c = N.get(this), d = c[a + "queue"], e = c[a + "queueHooks"], f = n.timers, g = d ? d.length : 0;
                for (c.finish = !0, n.queue(this, a, []), e && e.stop && e.stop.call(this, !0), b = f.length; b--;) f[b].elem === this && f[b].queue === a && (f[b].anim.stop(!0), f.splice(b, 1));
                for (b = 0; g > b; b++) d[b] && d[b].finish && d[b].finish.call(this);
                delete c.finish;
            });
        }
    }), n.each(["toggle", "show", "hide"], function (a, b) {
        var c = n.fn[b];
        n.fn[b] = function (a, d, e) {
            return null == a || "boolean" == typeof a ? c.apply(this, arguments) : this.animate(Xa(b, !0), a, d, e);
        };
    }), n.each({
        slideDown: Xa("show"),
        slideUp: Xa("hide"),
        slideToggle: Xa("toggle"),
        fadeIn: {opacity: "show"},
        fadeOut: {opacity: "hide"},
        fadeToggle: {opacity: "toggle"}
    }, function (a, b) {
        n.fn[a] = function (a, c, d) {
            return this.animate(b, a, c, d);
        };
    }), n.timers = [], n.fx.tick = function () {
        var a, b = 0, c = n.timers;
        for (Sa = n.now(); b < c.length; b++) a = c[b], a() || c[b] !== a || c.splice(b--, 1);
        c.length || n.fx.stop(), Sa = void 0;
    }, n.fx.timer = function (a) {
        n.timers.push(a), a() ? n.fx.start() : n.timers.pop();
    }, n.fx.interval = 13, n.fx.start = function () {
        Ta || (Ta = a.setInterval(n.fx.tick, n.fx.interval));
    }, n.fx.stop = function () {
        a.clearInterval(Ta), Ta = null;
    }, n.fx.speeds = {slow: 600, fast: 200, _default: 400}, n.fn.delay = function (b, c) {
        return b = n.fx ? n.fx.speeds[b] || b : b, c = c || "fx", this.queue(c, function (c, d) {
            var e = a.setTimeout(c, b);
            d.stop = function () {
                a.clearTimeout(e);
            };
        });
    }, function () {
        var a = d.createElement("input"), b = d.createElement("select"), c = b.appendChild(d.createElement("option"));
        a.type = "checkbox", l.checkOn = "" !== a.value, l.optSelected = c.selected, b.disabled = !0, l.optDisabled = !c.disabled, a = d.createElement("input"), a.value = "t", a.type = "radio", l.radioValue = "t" === a.value;
    }();
    var ab, bb = n.expr.attrHandle;
    n.fn.extend({
        attr: function (a, b) {
            return K(this, n.attr, a, b, arguments.length > 1);
        }, removeAttr: function (a) {
            return this.each(function () {
                n.removeAttr(this, a);
            });
        }
    }), n.extend({
        attr: function (a, b, c) {
            var d, e, f = a.nodeType;
            if (3 !== f && 8 !== f && 2 !== f) return "undefined" == typeof a.getAttribute ? n.prop(a, b, c) : (1 === f && n.isXMLDoc(a) || (b = b.toLowerCase(), e = n.attrHooks[b] || (n.expr.match.bool.test(b) ? ab : void 0)), void 0 !== c ? null === c ? void n.removeAttr(a, b) : e && "set" in e && void 0 !== (d = e.set(a, c, b)) ? d : (a.setAttribute(b, c + ""), c) : e && "get" in e && null !== (d = e.get(a, b)) ? d : (d = n.find.attr(a, b), null == d ? void 0 : d));
        }, attrHooks: {
            type: {
                set: function (a, b) {
                    if (!l.radioValue && "radio" === b && n.nodeName(a, "input")) {
                        var c = a.value;
                        return a.setAttribute("type", b), c && (a.value = c), b;
                    }
                }
            }
        }, removeAttr: function (a, b) {
            var c, d, e = 0, f = b && b.match(G);
            if (f && 1 === a.nodeType) while (c = f[e++]) d = n.propFix[c] || c, n.expr.match.bool.test(c) && (a[d] = !1), a.removeAttribute(c);
        }
    }), ab = {
        set: function (a, b, c) {
            return b === !1 ? n.removeAttr(a, c) : a.setAttribute(c, c), c;
        }
    }, n.each(n.expr.match.bool.source.match(/\w+/g), function (a, b) {
        var c = bb[b] || n.find.attr;
        bb[b] = function (a, b, d) {
            var e, f;
            return d || (f = bb[b], bb[b] = e, e = null != c(a, b, d) ? b.toLowerCase() : null, bb[b] = f), e;
        };
    });
    var cb = /^(?:input|select|textarea|button)$/i, db = /^(?:a|area)$/i;
    n.fn.extend({
        prop: function (a, b) {
            return K(this, n.prop, a, b, arguments.length > 1);
        }, removeProp: function (a) {
            return this.each(function () {
                delete this[n.propFix[a] || a];
            });
        }
    }), n.extend({
        prop: function (a, b, c) {
            var d, e, f = a.nodeType;
            if (3 !== f && 8 !== f && 2 !== f) return 1 === f && n.isXMLDoc(a) || (b = n.propFix[b] || b, e = n.propHooks[b]), void 0 !== c ? e && "set" in e && void 0 !== (d = e.set(a, c, b)) ? d : a[b] = c : e && "get" in e && null !== (d = e.get(a, b)) ? d : a[b];
        }, propHooks: {
            tabIndex: {
                get: function (a) {
                    var b = n.find.attr(a, "tabindex");
                    return b ? parseInt(b, 10) : cb.test(a.nodeName) || db.test(a.nodeName) && a.href ? 0 : -1;
                }
            }
        }, propFix: {"for": "htmlFor", "class": "className"}
    }), l.optSelected || (n.propHooks.selected = {
        get: function (a) {
            var b = a.parentNode;
            return b && b.parentNode && b.parentNode.selectedIndex, null;
        }, set: function (a) {
            var b = a.parentNode;
            b && (b.selectedIndex, b.parentNode && b.parentNode.selectedIndex);
        }
    }), n.each(["tabIndex", "readOnly", "maxLength", "cellSpacing", "cellPadding", "rowSpan", "colSpan", "useMap", "frameBorder", "contentEditable"], function () {
        n.propFix[this.toLowerCase()] = this;
    });
    var eb = /[\t\r\n\f]/g;

    function fb(a) {
        return a.getAttribute && a.getAttribute("class") || "";
    }

    n.fn.extend({
        addClass: function (a) {
            var b, c, d, e, f, g, h, i = 0;
            if (n.isFunction(a)) return this.each(function (b) {
                n(this).addClass(a.call(this, b, fb(this)));
            });
            if ("string" == typeof a && a) {
                b = a.match(G) || [];
                while (c = this[i++]) if (e = fb(c), d = 1 === c.nodeType && (" " + e + " ").replace(eb, " ")) {
                    g = 0;
                    while (f = b[g++]) d.indexOf(" " + f + " ") < 0 && (d += f + " ");
                    h = n.trim(d), e !== h && c.setAttribute("class", h);
                }
            }
            return this;
        }, removeClass: function (a) {
            var b, c, d, e, f, g, h, i = 0;
            if (n.isFunction(a)) return this.each(function (b) {
                n(this).removeClass(a.call(this, b, fb(this)));
            });
            if (!arguments.length) return this.attr("class", "");
            if ("string" == typeof a && a) {
                b = a.match(G) || [];
                while (c = this[i++]) if (e = fb(c), d = 1 === c.nodeType && (" " + e + " ").replace(eb, " ")) {
                    g = 0;
                    while (f = b[g++]) while (d.indexOf(" " + f + " ") > -1) d = d.replace(" " + f + " ", " ");
                    h = n.trim(d), e !== h && c.setAttribute("class", h);
                }
            }
            return this;
        }, toggleClass: function (a, b) {
            var c = typeof a;
            return "boolean" == typeof b && "string" === c ? b ? this.addClass(a) : this.removeClass(a) : n.isFunction(a) ? this.each(function (c) {
                n(this).toggleClass(a.call(this, c, fb(this), b), b);
            }) : this.each(function () {
                var b, d, e, f;
                if ("string" === c) {
                    d = 0, e = n(this), f = a.match(G) || [];
                    while (b = f[d++]) e.hasClass(b) ? e.removeClass(b) : e.addClass(b);
                } else void 0 !== a && "boolean" !== c || (b = fb(this), b && N.set(this, "__className__", b), this.setAttribute && this.setAttribute("class", b || a === !1 ? "" : N.get(this, "__className__") || ""));
            });
        }, hasClass: function (a) {
            var b, c, d = 0;
            b = " " + a + " ";
            while (c = this[d++]) if (1 === c.nodeType && (" " + fb(c) + " ").replace(eb, " ").indexOf(b) > -1) return !0;
            return !1;
        }
    });
    var gb = /\r/g, hb = /[\x20\t\r\n\f]+/g;
    n.fn.extend({
        val: function (a) {
            var b, c, d, e = this[0];
            {
                if (arguments.length) return d = n.isFunction(a), this.each(function (c) {
                    var e;
                    1 === this.nodeType && (e = d ? a.call(this, c, n(this).val()) : a, null == e ? e = "" : "number" == typeof e ? e += "" : n.isArray(e) && (e = n.map(e, function (a) {
                        return null == a ? "" : a + "";
                    })), b = n.valHooks[this.type] || n.valHooks[this.nodeName.toLowerCase()], b && "set" in b && void 0 !== b.set(this, e, "value") || (this.value = e));
                });
                if (e) return b = n.valHooks[e.type] || n.valHooks[e.nodeName.toLowerCase()], b && "get" in b && void 0 !== (c = b.get(e, "value")) ? c : (c = e.value, "string" == typeof c ? c.replace(gb, "") : null == c ? "" : c);
            }
        }
    }), n.extend({
        valHooks: {
            option: {
                get: function (a) {
                    var b = n.find.attr(a, "value");
                    return null != b ? b : n.trim(n.text(a)).replace(hb, " ");
                }
            }, select: {
                get: function (a) {
                    for (var b, c, d = a.options, e = a.selectedIndex, f = "select-one" === a.type || 0 > e, g = f ? null : [], h = f ? e + 1 : d.length, i = 0 > e ? h : f ? e : 0; h > i; i++) if (c = d[i], (c.selected || i === e) && (l.optDisabled ? !c.disabled : null === c.getAttribute("disabled")) && (!c.parentNode.disabled || !n.nodeName(c.parentNode, "optgroup"))) {
                        if (b = n(c).val(), f) return b;
                        g.push(b);
                    }
                    return g;
                }, set: function (a, b) {
                    var c, d, e = a.options, f = n.makeArray(b), g = e.length;
                    while (g--) d = e[g], (d.selected = n.inArray(n.valHooks.option.get(d), f) > -1) && (c = !0);
                    return c || (a.selectedIndex = -1), f;
                }
            }
        }
    }), n.each(["radio", "checkbox"], function () {
        n.valHooks[this] = {
            set: function (a, b) {
                return n.isArray(b) ? a.checked = n.inArray(n(a).val(), b) > -1 : void 0;
            }
        }, l.checkOn || (n.valHooks[this].get = function (a) {
            return null === a.getAttribute("value") ? "on" : a.value;
        });
    });
    var ib = /^(?:focusinfocus|focusoutblur)$/;
    n.extend(n.event, {
        trigger: function (b, c, e, f) {
            var g, h, i, j, l, m, o, p = [e || d], q = k.call(b, "type") ? b.type : b,
                r = k.call(b, "namespace") ? b.namespace.split(".") : [];
            if (h = i = e = e || d, 3 !== e.nodeType && 8 !== e.nodeType && !ib.test(q + n.event.triggered) && (q.indexOf(".") > -1 && (r = q.split("."), q = r.shift(), r.sort()), l = q.indexOf(":") < 0 && "on" + q, b = b[n.expando] ? b : new n.Event(q, "object" == typeof b && b), b.isTrigger = f ? 2 : 3, b.namespace = r.join("."), b.rnamespace = b.namespace ? new RegExp("(^|\\.)" + r.join("\\.(?:.*\\.|)") + "(\\.|$)") : null, b.result = void 0, b.target || (b.target = e), c = null == c ? [b] : n.makeArray(c, [b]), o = n.event.special[q] || {}, f || !o.trigger || o.trigger.apply(e, c) !== !1)) {
                if (!f && !o.noBubble && !n.isWindow(e)) {
                    for (j = o.delegateType || q, ib.test(j + q) || (h = h.parentNode); h; h = h.parentNode) p.push(h), i = h;
                    i === (e.ownerDocument || d) && p.push(i.defaultView || i.parentWindow || a);
                }
                g = 0;
                while ((h = p[g++]) && !b.isPropagationStopped()) b.type = g > 1 ? j : o.bindType || q, m = (N.get(h, "events") || {})[b.type] && N.get(h, "handle"), m && m.apply(h, c), m = l && h[l], m && m.apply && L(h) && (b.result = m.apply(h, c), b.result === !1 && b.preventDefault());
                return b.type = q, f || b.isDefaultPrevented() || o._default && o._default.apply(p.pop(), c) !== !1 || !L(e) || l && n.isFunction(e[q]) && !n.isWindow(e) && (i = e[l], i && (e[l] = null), n.event.triggered = q, e[q](), n.event.triggered = void 0, i && (e[l] = i)), b.result;
            }
        }, simulate: function (a, b, c) {
            var d = n.extend(new n.Event, c, {type: a, isSimulated: !0});
            n.event.trigger(d, null, b);
        }
    }), n.fn.extend({
        trigger: function (a, b) {
            return this.each(function () {
                n.event.trigger(a, b, this);
            });
        }, triggerHandler: function (a, b) {
            var c = this[0];
            return c ? n.event.trigger(a, b, c, !0) : void 0;
        }
    }), n.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "), function (a, b) {
        n.fn[b] = function (a, c) {
            return arguments.length > 0 ? this.on(b, null, a, c) : this.trigger(b);
        };
    }), n.fn.extend({
        hover: function (a, b) {
            return this.mouseenter(a).mouseleave(b || a);
        }
    }), l.focusin = "onfocusin" in a, l.focusin || n.each({focus: "focusin", blur: "focusout"}, function (a, b) {
        var c = function (a) {
            n.event.simulate(b, a.target, n.event.fix(a));
        };
        n.event.special[b] = {
            setup: function () {
                var d = this.ownerDocument || this, e = N.access(d, b);
                e || d.addEventListener(a, c, !0), N.access(d, b, (e || 0) + 1);
            }, teardown: function () {
                var d = this.ownerDocument || this, e = N.access(d, b) - 1;
                e ? N.access(d, b, e) : (d.removeEventListener(a, c, !0), N.remove(d, b));
            }
        };
    });
    var jb = a.location, kb = n.now(), lb = /\?/;
    n.parseJSON = function (a) {
        return JSON.parse(a + "");
    }, n.parseXML = function (b) {
        var c;
        if (!b || "string" != typeof b) return null;
        try {
            c = (new a.DOMParser).parseFromString(b, "text/xml");
        } catch (d) {
            c = void 0;
        }
        return c && !c.getElementsByTagName("parsererror").length || n.error("Invalid XML: " + b), c;
    };
    var mb = /#.*$/, nb = /([?&])_=[^&]*/, ob = /^(.*?):[ \t]*([^\r\n]*)$/gm,
        pb = /^(?:about|app|app-storage|.+-extension|file|res|widget):$/, qb = /^(?:GET|HEAD)$/, rb = /^\/\//, sb = {},
        tb = {}, ub = "*/".concat("*"), vb = d.createElement("a");
    vb.href = jb.href;

    function wb(a) {
        return function (b, c) {
            "string" != typeof b && (c = b, b = "*");
            var d, e = 0, f = b.toLowerCase().match(G) || [];
            if (n.isFunction(c)) while (d = f[e++]) "+" === d[0] ? (d = d.slice(1) || "*", (a[d] = a[d] || []).unshift(c)) : (a[d] = a[d] || []).push(c);
        };
    }

    function xb(a, b, c, d) {
        var e = {}, f = a === tb;

        function g(h) {
            var i;
            return e[h] = !0, n.each(a[h] || [], function (a, h) {
                var j = h(b, c, d);
                return "string" != typeof j || f || e[j] ? f ? !(i = j) : void 0 : (b.dataTypes.unshift(j), g(j), !1);
            }), i;
        }

        return g(b.dataTypes[0]) || !e["*"] && g("*");
    }

    function yb(a, b) {
        var c, d, e = n.ajaxSettings.flatOptions || {};
        for (c in b) void 0 !== b[c] && ((e[c] ? a : d || (d = {}))[c] = b[c]);
        return d && n.extend(!0, a, d), a;
    }

    function zb(a, b, c) {
        var d, e, f, g, h = a.contents, i = a.dataTypes;
        while ("*" === i[0]) i.shift(), void 0 === d && (d = a.mimeType || b.getResponseHeader("Content-Type"));
        if (d) for (e in h) if (h[e] && h[e].test(d)) {
            i.unshift(e);
            break;
        }
        if (i[0] in c) f = i[0]; else {
            for (e in c) {
                if (!i[0] || a.converters[e + " " + i[0]]) {
                    f = e;
                    break;
                }
                g || (g = e);
            }
            f = f || g;
        }
        return f ? (f !== i[0] && i.unshift(f), c[f]) : void 0;
    }

    function Ab(a, b, c, d) {
        var e, f, g, h, i, j = {}, k = a.dataTypes.slice();
        if (k[1]) for (g in a.converters) j[g.toLowerCase()] = a.converters[g];
        f = k.shift();
        while (f) if (a.responseFields[f] && (c[a.responseFields[f]] = b), !i && d && a.dataFilter && (b = a.dataFilter(b, a.dataType)), i = f, f = k.shift()) if ("*" === f) f = i; else if ("*" !== i && i !== f) {
            if (g = j[i + " " + f] || j["* " + f], !g) for (e in j) if (h = e.split(" "), h[1] === f && (g = j[i + " " + h[0]] || j["* " + h[0]])) {
                g === !0 ? g = j[e] : j[e] !== !0 && (f = h[0], k.unshift(h[1]));
                break;
            }
            if (g !== !0) if (g && a["throws"]) b = g(b); else try {
                b = g(b);
            } catch (l) {
                return {state: "parsererror", error: g ? l : "No conversion from " + i + " to " + f};
            }
        }
        return {state: "success", data: b};
    }

    n.extend({
        active: 0,
        lastModified: {},
        etag: {},
        ajaxSettings: {
            url: jb.href,
            type: "GET",
            isLocal: pb.test(jb.protocol),
            global: !0,
            processData: !0,
            async: !0,
            contentType: "application/x-www-form-urlencoded; charset=UTF-8",
            accepts: {
                "*": ub,
                text: "text/plain",
                html: "text/html",
                xml: "application/xml, text/xml",
                json: "application/json, text/javascript"
            },
            contents: {xml: /\bxml\b/, html: /\bhtml/, json: /\bjson\b/},
            responseFields: {xml: "responseXML", text: "responseText", json: "responseJSON"},
            converters: {"* text": String, "text html": !0, "text json": n.parseJSON, "text xml": n.parseXML},
            flatOptions: {url: !0, context: !0}
        },
        ajaxSetup: function (a, b) {
            return b ? yb(yb(a, n.ajaxSettings), b) : yb(n.ajaxSettings, a);
        },
        ajaxPrefilter: wb(sb),
        ajaxTransport: wb(tb),
        ajax: function (b, c) {
            "object" == typeof b && (c = b, b = void 0), c = c || {};
            var e, f, g, h, i, j, k, l, m = n.ajaxSetup({}, c), o = m.context || m,
                p = m.context && (o.nodeType || o.jquery) ? n(o) : n.event, q = n.Deferred(),
                r = n.Callbacks("once memory"), s = m.statusCode || {}, t = {}, u = {}, v = 0, w = "canceled", x = {
                    readyState: 0, getResponseHeader: function (a) {
                        var b;
                        if (2 === v) {
                            if (!h) {
                                h = {};
                                while (b = ob.exec(g)) h[b[1].toLowerCase()] = b[2];
                            }
                            b = h[a.toLowerCase()];
                        }
                        return null == b ? null : b;
                    }, getAllResponseHeaders: function () {
                        return 2 === v ? g : null;
                    }, setRequestHeader: function (a, b) {
                        var c = a.toLowerCase();
                        return v || (a = u[c] = u[c] || a, t[a] = b), this;
                    }, overrideMimeType: function (a) {
                        return v || (m.mimeType = a), this;
                    }, statusCode: function (a) {
                        var b;
                        if (a) if (2 > v) for (b in a) s[b] = [s[b], a[b]]; else x.always(a[x.status]);
                        return this;
                    }, abort: function (a) {
                        var b = a || w;
                        return e && e.abort(b), z(0, b), this;
                    }
                };
            if (q.promise(x).complete = r.add, x.success = x.done, x.error = x.fail, m.url = ((b || m.url || jb.href) + "").replace(mb, "").replace(rb, jb.protocol + "//"), m.type = c.method || c.type || m.method || m.type, m.dataTypes = n.trim(m.dataType || "*").toLowerCase().match(G) || [""], null == m.crossDomain) {
                j = d.createElement("a");
                try {
                    j.href = m.url, j.href = j.href, m.crossDomain = vb.protocol + "//" + vb.host != j.protocol + "//" + j.host;
                } catch (y) {
                    m.crossDomain = !0;
                }
            }
            if (m.data && m.processData && "string" != typeof m.data && (m.data = n.param(m.data, m.traditional)), xb(sb, m, c, x), 2 === v) return x;
            k = n.event && m.global, k && 0 === n.active++ && n.event.trigger("ajaxStart"), m.type = m.type.toUpperCase(), m.hasContent = !qb.test(m.type), f = m.url, m.hasContent || (m.data && (f = m.url += (lb.test(f) ? "&" : "?") + m.data, delete m.data), m.cache === !1 && (m.url = nb.test(f) ? f.replace(nb, "$1_=" + kb++) : f + (lb.test(f) ? "&" : "?") + "_=" + kb++)), m.ifModified && (n.lastModified[f] && x.setRequestHeader("If-Modified-Since", n.lastModified[f]), n.etag[f] && x.setRequestHeader("If-None-Match", n.etag[f])), (m.data && m.hasContent && m.contentType !== !1 || c.contentType) && x.setRequestHeader("Content-Type", m.contentType), x.setRequestHeader("Accept", m.dataTypes[0] && m.accepts[m.dataTypes[0]] ? m.accepts[m.dataTypes[0]] + ("*" !== m.dataTypes[0] ? ", " + ub + "; q=0.01" : "") : m.accepts["*"]);
            for (l in m.headers) x.setRequestHeader(l, m.headers[l]);
            if (m.beforeSend && (m.beforeSend.call(o, x, m) === !1 || 2 === v)) return x.abort();
            w = "abort";
            for (l in{success: 1, error: 1, complete: 1}) x[l](m[l]);
            if (e = xb(tb, m, c, x)) {
                if (x.readyState = 1, k && p.trigger("ajaxSend", [x, m]), 2 === v) return x;
                m.async && m.timeout > 0 && (i = a.setTimeout(function () {
                    x.abort("timeout");
                }, m.timeout));
                try {
                    v = 1, e.send(t, z);
                } catch (y) {
                    if (!(2 > v)) throw y;
                    z(-1, y);
                }
            } else z(-1, "No Transport");

            function z(b, c, d, h) {
                var j, l, t, u, w, y = c;
                2 !== v && (v = 2, i && a.clearTimeout(i), e = void 0, g = h || "", x.readyState = b > 0 ? 4 : 0, j = b >= 200 && 300 > b || 304 === b, d && (u = zb(m, x, d)), u = Ab(m, u, x, j), j ? (m.ifModified && (w = x.getResponseHeader("Last-Modified"), w && (n.lastModified[f] = w), w = x.getResponseHeader("etag"), w && (n.etag[f] = w)), 204 === b || "HEAD" === m.type ? y = "nocontent" : 304 === b ? y = "notmodified" : (y = u.state, l = u.data, t = u.error, j = !t)) : (t = y, !b && y || (y = "error", 0 > b && (b = 0))), x.status = b, x.statusText = (c || y) + "", j ? q.resolveWith(o, [l, y, x]) : q.rejectWith(o, [x, y, t]), x.statusCode(s), s = void 0, k && p.trigger(j ? "ajaxSuccess" : "ajaxError", [x, m, j ? l : t]), r.fireWith(o, [x, y]), k && (p.trigger("ajaxComplete", [x, m]), --n.active || n.event.trigger("ajaxStop")));
            }

            return x;
        },
        getJSON: function (a, b, c) {
            return n.get(a, b, c, "json");
        },
        getScript: function (a, b) {
            return n.get(a, void 0, b, "script");
        }
    }), n.each(["get", "post"], function (a, b) {
        n[b] = function (a, c, d, e) {
            return n.isFunction(c) && (e = e || d, d = c, c = void 0), n.ajax(n.extend({
                url: a,
                type: b,
                dataType: e,
                data: c,
                success: d
            }, n.isPlainObject(a) && a));
        };
    }), n._evalUrl = function (a) {
        return n.ajax({url: a, type: "GET", dataType: "script", async: !1, global: !1, "throws": !0});
    }, n.fn.extend({
        wrapAll: function (a) {
            var b;
            return n.isFunction(a) ? this.each(function (b) {
                n(this).wrapAll(a.call(this, b));
            }) : (this[0] && (b = n(a, this[0].ownerDocument).eq(0).clone(!0), this[0].parentNode && b.insertBefore(this[0]), b.map(function () {
                var a = this;
                while (a.firstElementChild) a = a.firstElementChild;
                return a;
            }).append(this)), this);
        }, wrapInner: function (a) {
            return n.isFunction(a) ? this.each(function (b) {
                n(this).wrapInner(a.call(this, b));
            }) : this.each(function () {
                var b = n(this), c = b.contents();
                c.length ? c.wrapAll(a) : b.append(a);
            });
        }, wrap: function (a) {
            var b = n.isFunction(a);
            return this.each(function (c) {
                n(this).wrapAll(b ? a.call(this, c) : a);
            });
        }, unwrap: function () {
            return this.parent().each(function () {
                n.nodeName(this, "body") || n(this).replaceWith(this.childNodes);
            }).end();
        }
    }), n.expr.filters.hidden = function (a) {
        return !n.expr.filters.visible(a);
    }, n.expr.filters.visible = function (a) {
        return a.offsetWidth > 0 || a.offsetHeight > 0 || a.getClientRects().length > 0;
    };
    var Bb = /%20/g, Cb = /\[\]$/, Db = /\r?\n/g, Eb = /^(?:submit|button|image|reset|file)$/i,
        Fb = /^(?:input|select|textarea|keygen)/i;

    function Gb(a, b, c, d) {
        var e;
        if (n.isArray(b)) n.each(b, function (b, e) {
            c || Cb.test(a) ? d(a, e) : Gb(a + "[" + ("object" == typeof e && null != e ? b : "") + "]", e, c, d);
        }); else if (c || "object" !== n.type(b)) d(a, b); else for (e in b) Gb(a + "[" + e + "]", b[e], c, d);
    }

    n.param = function (a, b) {
        var c, d = [], e = function (a, b) {
            b = n.isFunction(b) ? b() : null == b ? "" : b, d[d.length] = encodeURIComponent(a) + "=" + encodeURIComponent(b);
        };
        if (void 0 === b && (b = n.ajaxSettings && n.ajaxSettings.traditional), n.isArray(a) || a.jquery && !n.isPlainObject(a)) n.each(a, function () {
            e(this.name, this.value);
        }); else for (c in a) Gb(c, a[c], b, e);
        return d.join("&").replace(Bb, "+");
    }, n.fn.extend({
        serialize: function () {
            return n.param(this.serializeArray());
        }, serializeArray: function () {
            return this.map(function () {
                var a = n.prop(this, "elements");
                return a ? n.makeArray(a) : this;
            }).filter(function () {
                var a = this.type;
                return this.name && !n(this).is(":disabled") && Fb.test(this.nodeName) && !Eb.test(a) && (this.checked || !X.test(a));
            }).map(function (a, b) {
                var c = n(this).val();
                return null == c ? null : n.isArray(c) ? n.map(c, function (a) {
                    return {name: b.name, value: a.replace(Db, "\r\n")};
                }) : {name: b.name, value: c.replace(Db, "\r\n")};
            }).get();
        }
    }), n.ajaxSettings.xhr = function () {
        try {
            return new a.XMLHttpRequest;
        } catch (b) {
        }
    };
    var Hb = {0: 200, 1223: 204}, Ib = n.ajaxSettings.xhr();
    l.cors = !!Ib && "withCredentials" in Ib, l.ajax = Ib = !!Ib, n.ajaxTransport(function (b) {
        var c, d;
        return l.cors || Ib && !b.crossDomain ? {
            send: function (e, f) {
                var g, h = b.xhr();
                if (h.open(b.type, b.url, b.async, b.username, b.password), b.xhrFields) for (g in b.xhrFields) h[g] = b.xhrFields[g];
                b.mimeType && h.overrideMimeType && h.overrideMimeType(b.mimeType), b.crossDomain || e["X-Requested-With"] || (e["X-Requested-With"] = "XMLHttpRequest");
                for (g in e) h.setRequestHeader(g, e[g]);
                c = function (a) {
                    return function () {
                        c && (c = d = h.onload = h.onerror = h.onabort = h.onreadystatechange = null, "abort" === a ? h.abort() : "error" === a ? "number" != typeof h.status ? f(0, "error") : f(h.status, h.statusText) : f(Hb[h.status] || h.status, h.statusText, "text" !== (h.responseType || "text") || "string" != typeof h.responseText ? {binary: h.response} : {text: h.responseText}, h.getAllResponseHeaders()));
                    };
                }, h.onload = c(), d = h.onerror = c("error"), void 0 !== h.onabort ? h.onabort = d : h.onreadystatechange = function () {
                    4 === h.readyState && a.setTimeout(function () {
                        c && d();
                    });
                }, c = c("abort");
                try {
                    h.send(b.hasContent && b.data || null);
                } catch (i) {
                    if (c) throw i;
                }
            }, abort: function () {
                c && c();
            }
        } : void 0;
    }), n.ajaxSetup({
        accepts: {script: "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"},
        contents: {script: /\b(?:java|ecma)script\b/},
        converters: {
            "text script": function (a) {
                return n.globalEval(a), a;
            }
        }
    }), n.ajaxPrefilter("script", function (a) {
        void 0 === a.cache && (a.cache = !1), a.crossDomain && (a.type = "GET");
    }), n.ajaxTransport("script", function (a) {
        if (a.crossDomain) {
            var b, c;
            return {
                send: function (e, f) {
                    b = n("<script>").prop({charset: a.scriptCharset, src: a.url}).on("load error", c = function (a) {
                        b.remove(), c = null, a && f("error" === a.type ? 404 : 200, a.type);
                    }), d.head.appendChild(b[0]);
                }, abort: function () {
                    c && c();
                }
            };
        }
    });
    var Jb = [], Kb = /(=)\?(?=&|$)|\?\?/;
    n.ajaxSetup({
        jsonp: "callback", jsonpCallback: function () {
            var a = Jb.pop() || n.expando + "_" + kb++;
            return this[a] = !0, a;
        }
    }), n.ajaxPrefilter("json jsonp", function (b, c, d) {
        var e, f, g,
            h = b.jsonp !== !1 && (Kb.test(b.url) ? "url" : "string" == typeof b.data && 0 === (b.contentType || "").indexOf("application/x-www-form-urlencoded") && Kb.test(b.data) && "data");
        return h || "jsonp" === b.dataTypes[0] ? (e = b.jsonpCallback = n.isFunction(b.jsonpCallback) ? b.jsonpCallback() : b.jsonpCallback, h ? b[h] = b[h].replace(Kb, "$1" + e) : b.jsonp !== !1 && (b.url += (lb.test(b.url) ? "&" : "?") + b.jsonp + "=" + e), b.converters["script json"] = function () {
            return g || n.error(e + " was not called"), g[0];
        }, b.dataTypes[0] = "json", f = a[e], a[e] = function () {
            g = arguments;
        }, d.always(function () {
            void 0 === f ? n(a).removeProp(e) : a[e] = f, b[e] && (b.jsonpCallback = c.jsonpCallback, Jb.push(e)), g && n.isFunction(f) && f(g[0]), g = f = void 0;
        }), "script") : void 0;
    }), n.parseHTML = function (a, b, c) {
        if (!a || "string" != typeof a) return null;
        "boolean" == typeof b && (c = b, b = !1), b = b || d;
        var e = x.exec(a), f = !c && [];
        return e ? [b.createElement(e[1])] : (e = ca([a], b, f), f && f.length && n(f).remove(), n.merge([], e.childNodes));
    };
    var Lb = n.fn.load;
    n.fn.load = function (a, b, c) {
        if ("string" != typeof a && Lb) return Lb.apply(this, arguments);
        var d, e, f, g = this, h = a.indexOf(" ");
        return h > -1 && (d = n.trim(a.slice(h)), a = a.slice(0, h)), n.isFunction(b) ? (c = b, b = void 0) : b && "object" == typeof b && (e = "POST"), g.length > 0 && n.ajax({
            url: a,
            type: e || "GET",
            dataType: "html",
            data: b
        }).done(function (a) {
            f = arguments, g.html(d ? n("<div>").append(n.parseHTML(a)).find(d) : a);
        }).always(c && function (a, b) {
            g.each(function () {
                c.apply(this, f || [a.responseText, b, a]);
            });
        }), this;
    }, n.each(["ajaxStart", "ajaxStop", "ajaxComplete", "ajaxError", "ajaxSuccess", "ajaxSend"], function (a, b) {
        n.fn[b] = function (a) {
            return this.on(b, a);
        };
    }), n.expr.filters.animated = function (a) {
        return n.grep(n.timers, function (b) {
            return a === b.elem;
        }).length;
    };

    function Mb(a) {
        return n.isWindow(a) ? a : 9 === a.nodeType && a.defaultView;
    }

    n.offset = {
        setOffset: function (a, b, c) {
            var d, e, f, g, h, i, j, k = n.css(a, "position"), l = n(a), m = {};
            "static" === k && (a.style.position = "relative"), h = l.offset(), f = n.css(a, "top"), i = n.css(a, "left"), j = ("absolute" === k || "fixed" === k) && (f + i).indexOf("auto") > -1, j ? (d = l.position(), g = d.top, e = d.left) : (g = parseFloat(f) || 0, e = parseFloat(i) || 0), n.isFunction(b) && (b = b.call(a, c, n.extend({}, h))), null != b.top && (m.top = b.top - h.top + g), null != b.left && (m.left = b.left - h.left + e), "using" in b ? b.using.call(a, m) : l.css(m);
        }
    }, n.fn.extend({
        offset: function (a) {
            if (arguments.length) return void 0 === a ? this : this.each(function (b) {
                n.offset.setOffset(this, a, b);
            });
            var b, c, d = this[0], e = {top: 0, left: 0}, f = d && d.ownerDocument;
            if (f) return b = f.documentElement, n.contains(b, d) ? (e = d.getBoundingClientRect(), c = Mb(f), {
                top: e.top + c.pageYOffset - b.clientTop,
                left: e.left + c.pageXOffset - b.clientLeft
            }) : e;
        }, position: function () {
            if (this[0]) {
                var a, b, c = this[0], d = {top: 0, left: 0};
                return "fixed" === n.css(c, "position") ? b = c.getBoundingClientRect() : (a = this.offsetParent(), b = this.offset(), n.nodeName(a[0], "html") || (d = a.offset()), d.top += n.css(a[0], "borderTopWidth", !0), d.left += n.css(a[0], "borderLeftWidth", !0)), {
                    top: b.top - d.top - n.css(c, "marginTop", !0),
                    left: b.left - d.left - n.css(c, "marginLeft", !0)
                };
            }
        }, offsetParent: function () {
            return this.map(function () {
                var a = this.offsetParent;
                while (a && "static" === n.css(a, "position")) a = a.offsetParent;
                return a || Ea;
            });
        }
    }), n.each({scrollLeft: "pageXOffset", scrollTop: "pageYOffset"}, function (a, b) {
        var c = "pageYOffset" === b;
        n.fn[a] = function (d) {
            return K(this, function (a, d, e) {
                var f = Mb(a);
                return void 0 === e ? f ? f[b] : a[d] : void(f ? f.scrollTo(c ? f.pageXOffset : e, c ? e : f.pageYOffset) : a[d] = e);
            }, a, d, arguments.length);
        };
    }), n.each(["top", "left"], function (a, b) {
        n.cssHooks[b] = Ga(l.pixelPosition, function (a, c) {
            return c ? (c = Fa(a, b), Ba.test(c) ? n(a).position()[b] + "px" : c) : void 0;
        });
    }), n.each({Height: "height", Width: "width"}, function (a, b) {
        n.each({padding: "inner" + a, content: b, "": "outer" + a}, function (c, d) {
            n.fn[d] = function (d, e) {
                var f = arguments.length && (c || "boolean" != typeof d),
                    g = c || (d === !0 || e === !0 ? "margin" : "border");
                return K(this, function (b, c, d) {
                    var e;
                    return n.isWindow(b) ? b.document.documentElement["client" + a] : 9 === b.nodeType ? (e = b.documentElement, Math.max(b.body["scroll" + a], e["scroll" + a], b.body["offset" + a], e["offset" + a], e["client" + a])) : void 0 === d ? n.css(b, c, g) : n.style(b, c, d, g);
                }, b, f ? d : void 0, f, null);
            };
        });
    }), n.fn.extend({
        bind: function (a, b, c) {
            return this.on(a, null, b, c);
        }, unbind: function (a, b) {
            return this.off(a, null, b);
        }, delegate: function (a, b, c, d) {
            return this.on(b, a, c, d);
        }, undelegate: function (a, b, c) {
            return 1 === arguments.length ? this.off(a, "**") : this.off(b, a || "**", c);
        }, size: function () {
            return this.length;
        }
    }), n.fn.andSelf = n.fn.addBack, "function" == typeof define && define.amd && define("jquery", [], function () {
        return n;
    });
    var Nb = a.jQuery, Ob = a.$;
    return n.noConflict = function (b) {
        return a.$ === n && (a.$ = Ob), b && a.jQuery === n && (a.jQuery = Nb), n;
    }, b || (a.jQuery = a.$ = n), n;
});

var srv = function () {
    var show_log = !1, user_touching = !1,
        isIE = document.documentMode && (8 == document.documentMode || 9 == document.documentMode),
        eventsHandlers = {form_loaded: []},
        touch_css = {"-ms-touch-action": "manipulation", "touch-action": "manipulation"},
        isPreview = -1 < document.location.pathname.indexOf("/survey/manage/preview/"),
        PUBLIC_LANDING = -1 < document.location.pathname.indexOf("/survey/public/take-survey/"),
        LOCATION_REF = PUBLIC_LANDING ? document.location.href + (document.querySelector("#public-survey-page-querystring") && document.querySelector("#public-survey-page-querystring").value) : document.location.href,
        ALL_KEYS = [], FROM_DOMAIN = "", FIRST_INIT = !0, PM_INIT = !1;

    function base() {
        return {
            current_block: "",
            resize_int: 0,
            modalFirst: !0,
            modal_open: !1,
            modal_dismissed: !1,
            followUpId: !1,
            sendMode: "insert",
            hide_on_init: [],
            lock_submit: !1,
            required_when_visible: [],
            version: "1.3",
            domain: "mopinion",
            div_name: "surveyContent",
            remote: !0,
            outer_modal: "",
            prev_page: "",
            next_page: "",
            current_page: 1,
            page_count: 1,
            page_history: {},
            rules_set: [],
            show_progress: !1,
            window_viewport: "",
            document_title: "",
            form_completion_ratio: 0,
            trigger_method: "passive",
            is_loading: !0,
            show_button: !1,
            cookie_expire: 365,
            slider: {},
            button: {},
            ga_id: "",
            error_messages: {
                deflt: "Something went wrong",
                required: "This field is required",
                required_multi: "All fields are required",
                invalid_number: "This is not a valid number",
                invalid_phone: "This is not a valid phone number",
                invalid_email: "This is not a valid email address",
                too_short: "The answer is too short",
                too_long: "The answer is too long"
            }
        };
    }

    function opener(e) {
        var r, t, a = (e = e || {}).key;
        if (void 0 === a) return srv.log("Formkey required", !0, "warn"), !1;
        if (srv.hasOwnProperty(a)) {
            r = a, (t = function () {
                setTimeout(function () {
                    srv[r].is_loading ? t() : (srv[r] = __MS.extend({}, srv[r], e), "proactive" === srv[r].trigger_method || "exit" === srv[r].trigger_method || "force" === srv[r].trigger_method || "preview" === srv[r].trigger_method ? "slide" == srv[r].slider.type ? srv.preloadSlider(r, "open") : srv.openModal(!0, r) : "passive" === srv[r].trigger_method && void 0 === srv[r].survey_properties && srv.showButton(r));
                }, 50);
            })();
        } else srv.hasOwnProperty(a) || srv.loadSurvey(e, function () {
            srv.open(e);
        });
    }

    function loadSurvey(e, r) {
        if ("true" == getCookie("Mopinion_Debug") && (this.show_log = !0), !srv.hasOwnProperty(e.key) || srv[e.key].is_loading) {
            if ("object" != typeof e) return srv.log("Form key required", !0, "warn"), !1;
            FROM_DOMAIN ? e.domain = FROM_DOMAIN : FROM_DOMAIN = e.domain;
            var t = e.key;
            if (-1 == ALL_KEYS.indexOf(t) && ALL_KEYS.push(t), void 0 === e.testMode && (e.testMode = !1), this[t] = __MS.extend({}, base(), e), this[t].url = "https://" + this[t].domain + "/survey/public/stream?key=" + t + "&domain=" + this[t].domain, void 0 !== e.modal ? (this[t].outer_modal = e.modal, this[t].url += "&modal=" + this[t].outer_modal) : this[t].outer_modal = !0, this[t].url += "&version=" + this[t].version, !this[t].div_name) return srv.log("Output div is not set", !0, "warn"), !1;
            if (srv.log("Loading Mopinion Feedback Form with key " + t, !1, "info"), FIRST_INIT) this.loadjQuery(t), FIRST_INIT = !1; else {
                var a = this, s = function () {
                    "function" == typeof srv.jQuery ? a.loadJSON(t, r) : setTimeout(s, 50);
                };
                s();
            }
        } else "function" == typeof r && r();
    }

    function loadjQuery(e, r) {
        var t = this, a = "https://" + this[e].domain + "/assets/js/jquery-2.2.4.min.js", s = function () {
            return void 0 !== window.jQuery ? "jQuery" : void 0 !== window.$ && window.$.fn && window.$.fn.jquery ? "$" : void 0;
        }, o = s();
        o ? !function (e) {
            var r = e.split(".");
            if (1 < r.length) {
                if (1 == Number(r[0])) {
                    if (10 <= Number(r[1])) return !0;
                } else if (2 <= Number(r[0])) return !0;
                return !1;
            }
            return !1;
        }(window[s()].fn.jquery) ? srv.appendScript(a, "SRVQUERY", !1, !1, function () {
            t.loadjQueryHandler(!0, e, r);
        }) : (t.jQuery = window[o], t.loadHelpers(e), t.loadJSON(e, r)) : srv.appendScript(a, "SRVQUERY", !1, !1, function () {
            t.loadjQueryHandler(!1, e, r);
        });
    }

    function loadjQueryHandler(e, r, t) {
        e ? srv.jQuery = window.jQuery.noConflict(!0) : (jQuery = window.jQuery.noConflict(!0), srv.jQuery = jQuery), this.loadHelpers(r), this.loadJSON(r, t);
    }

    function loadHelpers(e) {
        var r = "https://" + srv[e].domain;
        if (isIE) {
            var t = r + "/assets/surveys/" + srv[e].version + "/js/jquery.xdomainrequest.min.js";
            this.appendScript(t, "#XDOMAINHELPER#", !1, "jQuery");
        }
        document.documentMode < 9 && (srv.appendScript(r + "/assets/js/selectivzr.min.js", "selectivzr"), srv.appendScript(r + "/assets/js/html5shiv.min.js", "html5shiv"), srv.appendScript(r + "/assets/js/respond.min.js", "respond"), srv.appendStyle(r + "/assets/css/surveys/survey-IE8.css", "ie8style"));
    }

    function loadJSON(e, r) {
        r = "function" == typeof r && r, this.appendScript(srv[e].url, "SRVvars" + e, !1, !1, r);
    }

    function generateHTML(e, a) {
        var r = !1, t = current_block.properties, s = t.elements || !1, o = t.required, n = "req",
            i = t.format ? t.format : "", l = t.placeholder ? t.placeholder : "", d = current_block.id, c = t.value,
            u = srv.jQuery("<span>").addClass("required-mark").html("*"), v = "section_break" === e ? "h3" : "legend",
            p = "section_break" === e ? "section-title" : "block-title";
        if (current_block.tooltip) {
            r = srv.jQuery("<a>").addClass("tooltip").attr("href", "#").css(touch_css).html(' <i class="fa fa-question"></i> <span>' + current_block.tooltip + "</span>");
            p += " has-tooltip";
        }
        var f = srv.jQuery("<" + v + ">").addClass(p).html(current_block.title).append(r);
        if (o && f.append(u), $block = srv.jQuery("<div>").attr("id", d).addClass("control-group question").append(f), "input" === e) X = srv.jQuery("<input>").attr("id", e + "_" + d).attr("type", "text").addClass(i).prop("placeholder", l).css(touch_css).val(c), o && X.addClass(n), iOSTest("fixed_positioning") && X.on("focus", function (e) {
            srv[a].outer_modal || srv.callParentWindow({fn: '(function(){ srv.jQuery("[data-key=' + a + ']").closest(".surveyWindowWrap").css({top:srv.jQuery(document).scrollTop(),bottom:"auto"}) })'});
        }).on("blur", function (e) {
            srv[a].outer_modal || srv.callParentWindow({fn: '(function(){ srv.jQuery("[data-key=' + a + ']").closest(".surveyWindowWrap").css({position:"fixed",top:0,bottom:0}); setTimeout(function() { srv.resizeForm({update:true}); },100); })'});
        }), iOSTest("scroll_on_input") && X.on("focus", function (e) {
            if (!srv[a].outer_modal) {
                var r = "slide" == srv[a].slider.type ? 'srv.jQuery("[data-key=' + a + ']").closest(".surveySliderScroller")' : 'srv.jQuery("[data-key=' + a + ']").closest(".surveyWindowWrap")';
                setTimeout(function () {
                    srv.callParentWindow({fn: "(function() { srv.scrollPos = " + r + '.scrollTop(); srv.jQuery(window).on("scroll.fix_scroll", function() { srv.scrollTop = ' + r + ".scrollTop() }); })"});
                });
            }
        }).on("input", function (e) {
            if (!srv[a].outer_modal) {
                var r = "slide" == srv[a].slider.type ? 'srv.jQuery("[data-key=' + a + ']").closest(".surveySliderScroller")' : 'srv.jQuery("[data-key=' + a + ']").closest(".surveyWindowWrap")';
                srv.callParentWindow({fn: "(function() { " + r + ".scrollTop(srv.scrollPos) })"});
            }
        }).on("blur", function () {
            srv[a].outer_modal || srv.callParentWindow({fn: '(function() { srv.jQuery(window).off("scroll.fix_scroll") })'});
        }), $block.append(X); else if ("textarea" === e) X = srv.jQuery("<textarea>").attr("id", e + "_" + d).addClass(i).prop("placeholder", l).css(touch_css).html(c), iOSTest("fixed_positioning") && X.on("focus", function (e) {
            srv[a].outer_modal || srv.callParentWindow({fn: '(function(){ srv.jQuery("[data-key=' + a + ']").closest(".surveyWindowWrap").css({top:srv.jQuery(document).scrollTop(),bottom:"auto"}) })'});
        }).on("blur", function (e) {
            srv[a].outer_modal || srv.callParentWindow({fn: '(function(){ srv.jQuery("[data-key=' + a + ']").closest(".surveyWindowWrap").css({position:"fixed",top:0,bottom:0}); setTimeout(function() { srv.resizeForm({update:true}); },100); })'});
        }), iOSTest("scroll_on_input") && X.on("focus", function (e) {
            if (!srv[a].outer_modal) {
                var r = "slide" == srv[a].slider.type ? 'srv.jQuery("[data-key=' + a + ']").closest(".surveySliderScroller")' : 'srv.jQuery("[data-key=' + a + ']").closest(".surveyWindowWrap")';
                setTimeout(function () {
                    srv.callParentWindow({fn: "(function() { srv.scrollPos = " + r + '.scrollTop(); srv.jQuery(window).on("scroll.fix_scroll", function() { srv.scrollTop = ' + r + ".scrollTop() }); })"});
                });
            }
        }).on("input", function (e) {
            if (!srv[a].outer_modal) {
                var r = "slide" == srv[a].slider.type ? 'srv.jQuery("[data-key=' + a + ']").closest(".surveySliderScroller")' : 'srv.jQuery("[data-key=' + a + ']").closest(".surveyWindowWrap")';
                srv.callParentWindow({fn: "(function() { " + r + ".scrollTop(srv.scrollPos) })"});
            }
        }).on("blur", function () {
            srv[a].outer_modal || srv.callParentWindow({fn: '(function() { srv.jQuery(window).off("scroll.fix_scroll") })'});
        }), o && X.addClass(n), $block.append(X); else if ("select" === e) {
            for (var y in X = srv.jQuery("<select>").attr("id", e + "_" + d), o && X.addClass(n), "" != l && (X.attr("data-placeholder", "true"), $placeholder = srv.jQuery("<option>").addClass("option_placeholder").attr({
                id: "option_" + d + "_0",
                disabled: !0
            }).prop("selected", !0).val("").css(touch_css).html(l), X.append($placeholder)), s) {
                "function" != typeof(k = s[y]) && (Pe = srv.jQuery("<option>").attr("id", "option_" + d + "_" + y).val(k.label).css(touch_css).html(k.label), t.selected == y && Pe.prop("selected", !0), X.append(Pe));
            }
            (_ = void 0 !== t.setScoreCookie && t.setScoreCookie) && X.on("change", function () {
                var e = X.parents(".control-group").attr("id");
                srv[a].outer_modal ? srv.setCookie("MSanswer." + a + "." + e, X.find("option:selected").val(), 0, "/", void 0, !1) : srv.callParentWindow({fn: "srv.setCookie('MSanswer." + a + "." + e + "','" + X.find("option:selected").val() + "',0,'/',undefined,false )"});
            }), $block.append(X);
        } else if (-1 < ["checkbox", "radio", "category", "gcr"].indexOf(e)) {
            var m = "radio" === e ? "rdo_" : "chk_", h = "category" === e || "gcr" === e ? "radio" : e,
                _ = void 0 !== t.setScoreCookie && t.setScoreCookie;
            if ("gcr" == e) var g = !!t.reverseScore && t.reverseScore; else g = !1;
            var b = {}, j = !1;
            for (var y in X = srv.jQuery("<div>"), s) {
                var k = s[y], Q = !1, w = d + "_" + y;
                if ("function" != typeof k) {
                    if (k.icon) Q = srv.jQuery("<i>").addClass(k.icon);
                    var C = k.value || k.label,
                        S = srv.jQuery("<input>").attr("type", h).attr("id", e + "_" + w).attr("name", e + "Group_" + d).on("click", function () {
                            srv.setValue(this, e + "_" + d, "radio" !== h);
                        }).css(touch_css).val(C);
                    isActive(y, current_block) && (S.prop("checked", "checked"), b[m + y] = k.label, 0, j = !0);
                    var x = srv.jQuery("<label>").attr("id", "label_" + w).attr("for", e + "_" + w).addClass(e + " inline").append(Q).css(touch_css).append(" " + k.label),
                        T = srv.jQuery("<div>").addClass(e + "-wrapper").append(S).append(x);
                    1 == t.show_as_buttons && (T.addClass("button"), X.addClass("button-container")), X.append(T);
                }
            }
            var M = j ? JSON.stringify(b) : "";
            I = srv.jQuery("<input>").attr("type", "hidden").attr("id", e + "_" + d).on("blur", function () {
                showError(srv.jQuery(this), __MS.validateField(this));
            }).val(M), o && I.addClass(n), _ && I.on("change", function () {
                var e = I.parents(".control-group").attr("id");
                srv[a].outer_modal ? srv.setCookie("MSanswer." + a + "." + e, I.val(), 0, "/", void 0, !1) : srv.callParentWindow({fn: "srv.setCookie('MSanswer." + a + "." + e + "','" + I.val() + "',0,'/',undefined,false )"});
            }), $block.addClass("clearfix").append(X).append(I).append('<p class="clearfix"></p>'), "gcr" == e && g && setTimeout(function () {
                var e = jQuery("#gcr_" + d + "_1").parent(), r = jQuery("#gcr_" + d + "_2").parent(),
                    t = jQuery("#gcr_" + d + "_3").parent();
                r.insertBefore(e), t.insertBefore(r);
            });
        } else if ("thumbs" === e) {
            X = srv.jQuery("<div>").addClass("button-container thumbs-container");
            var E = "", P = "";
            for (var y in s) {
                var $ = getPreselectValue("thumbs", a);
                (Z = t.preselectScore && $ && $ == s[y].value) && (E = s[y].value, P = d);
                var N = srv.jQuery("<div>").addClass("thumbs-wrapper button"),
                    O = srv.jQuery("<input>").attr("type", "radio").attr("id", "thumbs_" + d + "_" + y).attr("name", "thumbsGroup_" + d).attr("value", s[y].value).on("click", function () {
                        srv.setValue(this, e + "_" + d);
                    }).prop("checked", Z),
                    W = srv.jQuery("<label>").addClass("label_thumbs_" + s[y].value).attr("id", "label_thumbs_" + d + "_" + y).attr("for", "thumbs_" + d + "_" + y).css(touch_css).html('<i class="fa ' + s[y].icon + '" aria-hidden="true"></i>');
                s[y].label && t.labelsAsValue && W.append('<span class="thumbs_lbl_' + y + '">' + s[y].label + "</span>"), N.append(O, W), X.append(N);
            }
            if (I = srv.jQuery("<input>").attr("type", "hidden").attr("id", e + "_" + d).on("blur", function () {
                showError(srv.jQuery(this), __MS.validateField(this));
            }), (_ = void 0 !== t.setScoreCookie && t.setScoreCookie) && I.on("change", function () {
                var e = I.parents(".control-group").attr("id");
                srv[a].outer_modal ? srv.setCookie("MSanswer." + a + "." + e, I.val(), 0, "/", void 0, !1) : srv.callParentWindow({fn: "srv.setCookie('MSanswer." + a + "." + e + "','" + I.val() + "',0,'/',undefined,false )"});
            }), "" !== E) {
                I.val(E);
                var L = !!sessionStorage.getItem("emailscore");
                srv.jQuery(document).one("mopinion_ready", function (e) {
                    e.detail.key === a && setTimeout(function () {
                        jQuery("#thumbs_" + P).trigger("change"), L || (srv.send(!1, a), sessionStorage.setItem("emailscore", !0));
                    }, 500);
                });
            }
            o && I.addClass(n), $block.append(X).append(I);
        } else if ("rating" === e) {
            var A = srv.jQuery("<div>").addClass("choice-row"),
                I = srv.jQuery("<input>").attr("type", "hidden").attr("id", "rating_" + d);
            (_ = void 0 !== t.setScoreCookie && t.setScoreCookie) && I.on("change", function () {
                var e = I.parents(".control-group").attr("id");
                srv[a].outer_modal ? setCookie("MSanswer." + a + "." + e, I.val(), "session", "/", void 0, !1) : srv.callParentWindow({fn: "srv.setCookie('MSanswer." + a + "." + e + "','" + I.val() + "','session','/',undefined,false )"});
            });
            E = "", P = "";
            if (o && I.addClass(n), "numeric" == t.type) {
                var F = t.includeZero ? 0 : 1;
                for (M = t.checked && "number" == typeof t.checked[0] ? t.checked[0] : "", y = F; y < parseInt(t.scale) + 1; y++) {
                    $ = getPreselectValue("score", a);
                    (Z = t.preselectScore && $ && $ == y) && (E = y, P = d);
                    w = d + "_" + y;
                    var B = srv.jQuery("<input>").attr("type", "radio").attr("id", "rating_" + w).attr("name", "radioGroup_" + d).prop("checked", Z).on("click", function () {
                        srv.setValue(this, e + "_" + d);
                    }).css(touch_css).val(y);
                    t.checked && t.checked[0] == y && B.prop("checked", !0);
                    var q = srv.jQuery("<label>").attr("id", "label_" + w).attr("for", "rating_" + w).css(touch_css).html(y),
                        R = srv.jQuery("<div>").addClass("rating-choice").append(B).append(q);
                    A.append(R);
                }
                X = srv.jQuery("<div>").addClass("rating-group numeric").append('<span class="rating-prefix"></span>').append(A).append('<span class="rating-suffix"></span>');
            } else if ("bar" == t.type) {
                for (M = "", y = 1; y < 6; y++) {
                    if (1 == y) var H = "--"; else if (2 == y) H = "-"; else if (3 == y) H = "0"; else if (4 == y) H = "+"; else if (5 == y) H = "++";
                    w = d + "_" + y, B = srv.jQuery("<input>").attr("type", "radio").attr("id", "rating_" + w).attr("name", "radioGroup_" + d).on("click", function () {
                        srv.setValue(this, e + "_" + d);
                    }).css(touch_css).val(y), q = srv.jQuery("<label>").attr("id", "label_" + w).attr("for", "rating_" + w).css(touch_css).html("<span>" + H + "</span>"), R = srv.jQuery("<div>").addClass("rating-choice").append(B).append(q);
                    A.append(R);
                }
                X = srv.jQuery("<div>").addClass("rating-group bar").append('<span class="rating-prefix"></span>').append(A).append('<span class="rating-suffix"></span>');
            } else if ("emoji" == t.type) {
                for (var V = ["angry", "sad", "neutral", "happy", "extra-happy"], D = ($ = getPreselectValue("smiley", a), 0); D < V.length; D++) {
                    y = Number(D) + 1;
                    (Z = t.preselectScore && $ && $ == y) && (E = y, P = d);
                    w = d + "_" + y, B = srv.jQuery("<input>").attr("type", "radio").attr("id", "rating_" + w).prop("checked", Z).attr("name", "radioGroup_" + d).on("click", function () {
                        srv.setValue(this, e + "_" + d);
                    }).css(touch_css);
                    t.labelsAsValue ? B.val(t.emoji[y].label) : B.val(y);
                    q = srv.jQuery("<label>").attr("id", "label_" + w).attr("for", "rating_" + w).css(touch_css);
                    if (t.showCaptions && q.attr("data-label", t.emoji[y].label), supportsSvg()) !function (r, t) {
                        srv.jQuery.ajax({
                            url: "https://" + srv[a].domain + "/assets/img/surveys/emoji/" + t + ".svg",
                            type: "get",
                            dataType: "text"
                        }).done(function (e) {
                            r.addClass("emoji-" + t).html(e);
                        });
                    }(q, V[D]); else srv[a].jQuery("<img>").attr({
                        src: "https://" + srv[a].domain + "/assets/img/surveys/emoji/" + V[D] + ".png",
                        height: "100%",
                        width: "100%"
                    }).appendTo(q);
                    R = srv.jQuery("<div>").addClass("rating-choice").append(B).append(q);
                    A.append(R);
                }
                X = srv.jQuery("<div>").addClass("rating-group emoji").append('<span class="rating-prefix"></span>').append(A).append('<span class="rating-suffix"></span>'), t.showCaptions && X.addClass("show-labels");
            } else {
                var z = t.checked;
                M = "";
                $ = getPreselectValue("score", a);
                for (var y in s) {
                    if ("function" != typeof(k = s[y])) {
                        var U = t.labelsAsValue && "" !== k.label ? k.label : y;
                        (Z = t.preselectScore && $ && $ == y) && (E = y, P = d);
                        R = srv.jQuery("<input>").attr("type", "radio").attr("id", "Jrating").attr("name", "Jrating").prop("title", k.label).prop("checked", Z).css(touch_css).val(U);
                        z && z[0] == y && (R.attr("checked", "checked"), M = z[0]), A.append(R);
                    }
                }
                X = srv.jQuery("<div>").attr("id", "star_rating_" + d).addClass("rating-group stars").append('<span class="rating-prefix"></span>').append(A).append('<span class="rating-suffix"></span>');
                var Y = function (e) {
                    var r = srv.jQuery("#rating_" + d);
                    __MS.validateField(r[0]), enableNext(e);
                }, G = {
                    type: "rating",
                    block_id: d,
                    showCaptions: !!t.showCaptions,
                    callback: Y,
                    callback_data: a,
                    hidden: I
                };
                srv.makeStars(X, G);
            }
            if ("" !== E) {
                I.val(E);
                L = !!sessionStorage.getItem("emailscore");
                srv.jQuery(document).one("mopinion_ready", function (e) {
                    e.detail.key === a && setTimeout(function () {
                        "stars" == t.type && srv.jQuery("#star_rating_" + d).find('.ui-stars-star[data-value="' + E + '"]').click();
                        jQuery("#rating_" + P).trigger("change"), L || (srv.send(!1, a), sessionStorage.setItem("emailscore", !0));
                    }, 500);
                });
            } else M && I.val(M);
            $block.append(I).append(X);
        } else if ("nps" === e) {
            M = "";
            var J = srv.jQuery("<div>").addClass("choice-row");
            E = "", P = "", g = !!t.reverseScore && t.reverseScore, _ = void 0 !== t.setScoreCookie && t.setScoreCookie;
            if (g) for (y = 10; 0 <= y; y--) {
                $ = getPreselectValue("nps", a);
                (Z = t.preselectScore && $ && $ == y) && (E = y, P = d);
                w = d + "_" + y, S = srv.jQuery("<input>").attr("type", "radio").attr("id", "nps_" + w).attr("name", "radioGroup_" + d).prop("checked", Z).on("click", function () {
                    srv.setValue(this, e + "_" + d);
                }).css(touch_css).val(y);
                isActive(y, current_block) && (S.prop("checked", "checked"), M = y);
                x = srv.jQuery("<label>").attr("for", "nps_" + w).attr("id", "label_" + w).css(touch_css).html(y);
                var K = srv.jQuery("<div>").addClass("nps-choice").append(S).append(x);
                J.append(K);
            } else for (y = 0; y < 11; y++) {
                var Z;
                $ = getPreselectValue("nps", a);
                (Z = t.preselectScore && $ && $ == y) && (E = y, P = d);
                w = d + "_" + y, S = srv.jQuery("<input>").attr("type", "radio").attr("id", "nps_" + w).attr("name", "radioGroup_" + d).prop("checked", Z).on("click", function () {
                    srv.setValue(this, e + "_" + d);
                }).css(touch_css).val(y);
                isActive(y, current_block) && (S.prop("checked", "checked"), M = y);
                x = srv.jQuery("<label>").attr("for", "nps_" + w).attr("id", "label_" + w).css(touch_css).html(y), K = srv.jQuery("<div>").addClass("nps-choice").append(S).append(x);
                J.append(K);
            }
            var X = srv.jQuery("<div>").addClass("rating-group nps-group").append(J);
            I = srv.jQuery("<input>").attr("type", "hidden").attr("id", e + "_" + d).on("click", function () {
                showError(srv.jQuery(this), __MS.validateField(this));
            }).val(M);
            if (o && I.addClass(n), _ && I.on("change", function () {
                var e = I.parents(".control-group").attr("id");
                srv[a].outer_modal ? setCookie("MSanswer." + a + "." + e, I.val(), "session", "/", void 0, !1) : srv.callParentWindow({fn: "srv.setCookie('MSanswer." + a + "." + e + "','" + I.val() + "','session','/',undefined,false )"});
            }), $block.append(X).append(I), "" !== E) {
                I.val(E);
                L = !!sessionStorage.getItem("emailscore");
                srv.jQuery(document).one("mopinion_ready", function (e) {
                    e.detail.key === a && setTimeout(function () {
                        jQuery("#nps_" + P).trigger("change"), L || (srv.send(!1, a), sessionStorage.setItem("emailscore", !0));
                    }, 500);
                });
            }
        } else if ("ces" === e) {
            z = t.checked, M = "", g = !!t.reverseScore && t.reverseScore, _ = void 0 !== t.setScoreCookie && t.setScoreCookie, A = srv.jQuery("<div>").addClass("choice-row");
            for (var y in s) {
                if ("function" != typeof(k = s[y])) {
                    checked = !1, z && z[0] == y && (checked = !0, M = z[0]);
                    R = srv.jQuery("<input>").attr("type", "radio").attr("id", "Jrating").attr("name", "Jrating").prop("title", k.label).css(touch_css).val(y);
                    checked && R.attr("checked", checked), A.append(R);
                }
            }
            Y = function (e) {
                var r = srv.jQuery("#ces_" + d);
                __MS.validateField(r[0]), enableNext(e);
            };
            X = srv.jQuery("<div>").attr("id", "ces_rating_" + d).addClass("rating-group ces").append('<span class="ces-prefix"></span>').append(A).append('<span class="ces-suffix"></span>'), I = srv.jQuery("<input>").attr("type", "hidden").attr("id", "ces_" + d).val(M), _ && I.on("change", function () {
                var e = I.parents(".control-group").attr("id");
                srv[a].outer_modal ? setCookie("MSanswer." + a + "." + e, I.val(), "session", "/", void 0, !1) : srv.callParentWindow({fn: "srv.setCookie('MSanswer." + a + "." + e + "','" + I.val() + "','session','/',undefined,false )"});
            }), o && I.addClass(n);
            G = {type: "ces", block_id: d, showCaptions: !!t.showCaptions, callback: Y, callback_data: a, hidden: I};
            srv.makeStars(X, G), $block.append(X).append(I), g && setTimeout(function () {
                for (y = 1; y <= 5; y++) {
                    var e = jQuery("#ces_rating_" + d + ' div[data-value="' + y + '"]'),
                        r = jQuery("#ces_rating_" + d + ' div[data-value="' + (y - 1) + '"]');
                    e.insertBefore(r);
                }
                jQuery("#ces_rating_" + d).addClass("reversed");
            });
        } else if ("section_break" === e) X = srv.jQuery("<div>").attr("id", "section_description_" + d).addClass("section-description").html(t.description), $block.addClass("section").append(X); else if ("matrix" === e) {
            var ee = t.values, re = t.weights, te = srv.jQuery("<th>").addClass("matrix-subject-label"),
                ae = srv.jQuery("<tr>").append(te), se = srv.jQuery("<thead>").append(ae), oe = srv.jQuery("<tbody>");
            for (var ne in ee) if ("function" != typeof ee[ne]) {
                var ie = srv.jQuery("<th>").addClass("matrix-answer-label").html("<div><span>" + ee[ne].label + "</span></div>");
                ae.append(ie);
            }
            for (var le in ae.append('<th class="matrix-spacer"></th>'), re) if ("function" != typeof re[le]) {
                var de = srv.jQuery("<th>").addClass("matrix-answer-label").html("<div><span>" + re[le].label + "</span></div>");
                ae.append(de);
            }
            for (var y in s) {
                if ("function" != typeof(k = s[y])) {
                    var ce = srv.jQuery("<td>").addClass("matrix-subject").html(k.label);
                    je = srv.jQuery("<tr>").append(ce);
                    I = srv.jQuery("<input>").attr("type", "hidden").attr("id", "matrix_" + d + "_" + y).on("blur", function () {
                        showError(srv.jQuery(this), __MS.validateField(this));
                    });
                    for (var ne in o && I.addClass(n), ee) if ("function" != typeof ee[ne]) {
                        var ue = srv.jQuery("<input>").attr("type", "radio").attr("name", "matrix_" + d + "_" + y + "_value").attr("id", "matrix_" + d + "_" + y + "_value_" + ne).attr("title", ee[ne].label).on("click", function () {
                                setMatrix(this);
                            }).css(touch_css).val(ee[ne].label),
                            ve = srv.jQuery("<label>").attr("for", "matrix_" + d + "_" + y + "_value_" + ne).css(touch_css),
                            pe = srv.jQuery("<div>").addClass("matrix-answer-inner").append(ue).append(ve),
                            fe = srv.jQuery("<td>").addClass("matrix-answer").attr("data-label", ee[ne].label).append(pe);
                        je.append(fe);
                    }
                    for (var le in je.append('<td class="matrix-spacer"></td>'), re) if ("function" != typeof re[le]) {
                        var ye = srv.jQuery("<input>").attr("type", "radio").attr("name", "matrix_" + d + "_" + y + "_weight").attr("id", "matrix_" + d + "_" + y + "_weight_" + le).attr("title", re[le].label).on("click", function () {
                                setMatrix(this);
                            }).css(touch_css).val(re[le].label),
                            me = srv.jQuery("<label>").attr("for", "matrix_" + d + "_" + y + "_weight_" + le).css(touch_css),
                            he = srv.jQuery("<div>").addClass("matrix-answer-inner").append(ye).append(me),
                            _e = srv.jQuery("<td>").addClass("matrix-answer").attr("data-label", re[le].label).append(he);
                        je.append(_e);
                    }
                    je.append(I), oe.append(je);
                }
            }
            X = srv.jQuery("<div>").attr("id", "matrix_" + d).addClass("matrix-group");
            var ge = srv.jQuery("<table>").addClass("table table-striped matrix-table").append(se).append(oe);
            X.append(ge), $block.append(X);
        } else if ("likert" === e) {
            ee = t.values, te = srv.jQuery("<th>").addClass("likert-subject-label"), ae = srv.jQuery("<tr>").append(te), se = srv.jQuery("<thead>").append(ae), oe = srv.jQuery("<tbody>");
            for (var ne in ee) if ("function" != typeof ee[ne]) {
                var be = srv.jQuery("<th>").addClass("likert-answer-label").html("<div><span>" + ee[ne].label + "</span></div>");
                ae.append(be);
            }
            for (var y in s) {
                if ("function" != typeof(k = s[y])) {
                    ce = srv.jQuery("<td>").addClass("likert-subject").html(k.label), I = srv.jQuery("<input>").attr("type", "hidden").attr("id", "likert_" + d + "_" + y).on("blur", function () {
                        showError(srv.jQuery(this), __MS.validateField(this));
                    });
                    o && I.addClass(n);
                    var je = srv.jQuery("<tr>").append(ce);
                    for (var ne in ee) {
                        C = ee[ne];
                        var ke = srv.jQuery("<input>").attr("type", "radio").attr("name", "likert_" + d + "_" + y).attr("id", "likert_" + d + "_" + y + "_rdo_" + ne).attr("title", C.label).on("click", function () {
                                setLikert(this);
                            }).css(touch_css).val(C.label),
                            Qe = srv.jQuery("<label>").attr("for", "likert_" + d + "_" + y + "_rdo_" + ne).css(touch_css),
                            we = srv.jQuery("<div>").addClass("likert-answer-inner").append(ke).append(Qe);
                        $cell = srv.jQuery("<td>").addClass("likert-answer").attr("data-label", C.label).append(we), je.append($cell);
                    }
                    je.append(I), oe.append(je);
                }
            }
            X = srv.jQuery("<div>").attr("id", "likert_" + d).addClass("likert-group"), ge = srv.jQuery("<table>").addClass("table table-striped likert-table").append(se).append(oe);
            X.append(ge), $block.append(X);
        } else if ("contact" === e) {
            var Ce = srv.jQuery("<div>").addClass("contact-wrapper");
            if (s.name.show) {
                var Se = srv.jQuery("<div>").addClass("name-wrapper");
                if (s.title.show) {
                    var xe = srv.jQuery("<select>").attr("id", "contact_" + d + "_title").attr("data-contact", !0).addClass("span2").css(touch_css),
                        Te = Object.keys(s.title.options)[0];
                    for (var Me in"" !== s.title.options[Te] && (s.title.options[0] = ""), s.title.required && (xe.addClass(n), f.append(u)), s.title.options) {
                        var Ee = s.title.options[Me], Pe = srv.jQuery("<option>").val(Ee).html(Ee);
                        xe.append(Pe);
                    }
                    xe = srv.jQuery("<div>").addClass("title").append(xe);
                    Se.append(xe);
                }
                if (s.name.combine) name_placeholder = s.name.placeholder || ""; else {
                    var $e = srv.jQuery("<input>").attr("id", "contact_" + d + "_firstname").attr("data-contact", !0).attr("type", "text").attr("placeholder", s.name.subelements.firstname.placeholder || "").css(touch_css);
                    s.name.required && $e.addClass(n), $e = srv.jQuery("<div>").append($e), Se.append($e), name_placeholder = s.name.subelements.lastname.placeholder || "";
                }
                var Ne = srv.jQuery("<input>").attr("id", "contact_" + d + "_name").attr("data-contact", !0).attr("type", "text").attr("placeholder", name_placeholder).css(touch_css);
                s.name.required && (Ne.addClass(n), f.append(u)), Ne = srv.jQuery("<div>").append(Ne), Se.append(Ne);
            }
            if (s.email.show) {
                var Oe = srv.jQuery("<input>").attr("id", "contact_" + d + "_email").attr("type", "text").attr("data-contact", !0).attr("placeholder", s.email.placeholder || "").addClass("email").css(touch_css);
                s.email.required && Oe.addClass(n), Oe = srv.jQuery("<div>").append(Oe);
                var We = srv.jQuery("<div>").addClass("email-wrapper").append(Oe);
            }
            if (s.phone.show) {
                var Le = srv.jQuery("<input>").attr("id", "contact_" + d + "_phone").attr("type", "text").attr("data-contact", !0).attr("placeholder", s.phone.placeholder || "").addClass("phone").css(touch_css);
                if (s.phone.required && Le.addClass(n), s.phone2.show) {
                    var Ae = srv.jQuery("<input>").attr("id", "contact_" + d + "_phone2").attr("type", "text").attr("data-contact", !0).attr("placeholder", s.phone2.placeholder || "").addClass("phone").css(touch_css);
                    s.phone2.required && Ae.addClass(n), Ae = srv.jQuery("<div>").append(Ae);
                }
                Le = srv.jQuery("<div>").append(Le);
                var Ie = srv.jQuery("<div>").addClass("phone-wrapper").append(Le).append(Ae);
            }
            Ce.append(Se).append(We).append(Ie), $block.append(Ce);
        } else if ("screenshot" === e) {
            X = jQuery("<div>").attr("id", "screenshot_element_" + d).addClass("section-screenshot").html('<i class="fa fa-camera"></i>').on("click", function (e) {
                e.preventDefault();
                var r = srv[a].block_params[d].properties.hasOwnProperty("maskedSelectors") ? srv[a].block_params[d].properties.maskedSelectors : [];
                srv[a].outer_modal ? setTimeout(function () {
                    srv.initCapture({embedded: !0, block_id: d, key: a, maskSelectors: r});
                }) : setTimeout(function () {
                    srv.callParentWindow({
                        fn: "srv.initCapture",
                        data: {block_id: d, supportsPointerEvents: supportsPointerEvents(), key: a, maskedSelectors: r}
                    });
                });
            });
            srv.jQuery("<div>").addClass("remove-capture").html('<i class="fa fa-times"></i>').appendTo(X), srv.jQuery("<div>").addClass("detect-capture").html('<i class="fa fa-check"></i>').appendTo(X);
            $block.addClass("section").append(X), 1 == t.bottomScreen && ($block.addClass("absolute"), r && X.addClass("tooltip").append('<span class="tooltip">' + current_block.tooltip + "</span>"), o && X.append(u));
            srv.jQuery("<input>").attr({
                type: "hidden",
                id: "dom_" + d
            }).prependTo($block), srv.jQuery("<input>").attr({type: "hidden", id: "selector_" + d}).prependTo($block);
            var Fe = srv.jQuery("<input>").attr({type: "hidden", id: "screenshot_" + d}).on("blur", function () {
                showError(srv.jQuery(this), __MS.validateField(this));
            }).prependTo($block);
            o && Fe.addClass(n);
        } else if ("link" === e) {
            var Be = current_block.properties.hyperlinkhref || "", qe = current_block.properties.hyperlinktext || "";
            if (current_block.properties.show_as_buttons) var Re = "btn"; else Re = "";
            if (Be.indexOf("http://") < 0 && Be.indexOf("https://") < 0) var He = current_block.properties.hyperlinkprotocol || ""; else He = "";
            if ("link" == current_block.properties.linktype) qe = qe.replace("/*", '<a href="' + He + Be + '" class="' + Re + '" target="_blank">').replace("*/", "</a>"); else if ("function" == current_block.properties.linktype) {
                var Ve = current_block.properties.linktype_function;
                qe = "close_modal" == Ve ? qe.replace("/*", "<a href='javascript:;' onclick='srv.callParentWindow({fn:\"srv.closeModal\",data:\"" + a + "\"})' class='" + Re + "' >").replace("*/", "</a>") : "close_embedded" == Ve ? qe.replace("/*", "<a href='javascript:;' onclick=\"srv.jQuery('.close_custom_bg').click();\" class='" + Re + "' >").replace("*/", "</a>") : "next_page" == Ve ? qe.replace("/*", "<a href='javascript:;' onclick='srv.nextPage(false,\"" + a + "\")' class='" + Re + "' >").replace("*/", "</a>") : "prev_page" == Ve ? qe.replace("/*", "<a href='javascript:;' onclick='srv.prevPage(false,\"" + a + "\")' class='" + Re + "' >").replace("*/", "</a>") : qe.replace("/*", '<span data-attr="unknown function"').replace("*/", "</span>");
            } else qe = qe.replace("/*", '<a href="' + He + Be + '" class="' + Re + '" target="_blank">').replace("*/", "</a>");
            X = srv.jQuery("<div>").attr("id", "link_" + d).addClass("link_block").append(qe), "link" == current_block.properties.linktype && X.on("click", function () {
                srv.callParentWindow({
                    fn: "srv.triggerEvent",
                    data: {event: "redirect", key: a, formName: srv[a].survey_properties.name, url: He + Be}
                });
            }), $block.append(X);
        } else if ("website_data" === e) {
            for (var y in s) {
                var De = s[y], ze = e + "_" + De.type + "_" + d + "_" + y;
                ze = ze.replace(/\W/g, "");
                srv.jQuery("<input>").attr({type: "hidden", id: ze, name: ze}).appendTo($block);
                if (srv[a].outer_modal || PUBLIC_LANDING) fetchVariable({
                    type: De.type,
                    value: De.value,
                    hidden_id: ze,
                    embedded: !0,
                    key: a
                }); else {
                    var Ue = {type: De.type, value: encodeURIComponent(De.value), hidden_id: ze, key: a};
                    Ue = JSON.stringify(Ue), srv.callParentWindow({fn: "srv.fetchVariable", data: Ue});
                }
            }
            $block.css("display", "none");
        } else $block = srv.jQuery("<div>").html("[unknown type]");
        return $block;
    }

    function buildForm(l) {
        var e = srv[l].error_messages, r = srv[l].survey_text;
        e.deflt = r.deflt ? r.deflt : "Something went wrong", e.required = r.required ? r.required : "This field is required", e.required_multi = r.required_multi ? r.required_multi : "All fields are required", e.invalid_number = r.invalid_number ? r.invalid_number : "This is not a valid number", e.invalid_phone = r.invalid_phone ? r.invalid_phone : "This is not a valid phone number", e.invalid_email = r.invalid_email ? r.invalid_email : "This is not a valid email address", e.too_short = r.too_short ? r.too_short : "The answer is too short", e.too_long = r.too_long ? r.too_long : "The answer is too long", themeClass = srv[l].theme_class || "", srv.jQuery(document).one("touchstart", function () {
            user_touching = !0;
        }), supportsPointerEvents(!0);
        var t = srv.jQuery("#" + this[l].div_name);
        if (t.addClass("mopinion-survey-content"), !srv[l].outer_modal || !t.find('[data-key="' + l + '"]').length) {
            var a = !1;
            if (void 0 !== srv[l].survey_properties) {
                var s = !1, o = !1, d = !1, n = srv.jQuery("<div>").attr("id", "lastPage").addClass("last-page").css({
                        "min-height": "100px",
                        display: "none"
                    }).html(srv[l].survey_properties.exit_content),
                    i = srv.jQuery("<div>").addClass("completed-anim").append('<i class="fa fa-check"></i>');
                n.prepend(i);
                var c = srv.jQuery("<div>");
                if (!this[l].outer_modal) {
                    s = srv.jQuery("<div>").attr("id", "surveyHead"), o = srv.jQuery("<div>").attr("id", "surveyFoot");
                    if (srv[l].survey_properties.footer && o.html("<p>" + srv[l].survey_properties.footer + "</p>"), "" != srv[l].survey_properties.logo) var u = srv.jQuery("<img>").attr("id", "customerLogo").attr("src", srv[l].survey_properties.logo);
                    var v = srv.jQuery("<div>").attr("id", "surveyTitle").addClass("srv-title main-title").html("<h1>" + srv[l].survey_properties.title + "</h1>");
                    s.append(u).append(v);
                }
                $page = srv.jQuery("<div>").attr("id", "page1"), srv.jQuery(srv[l].block_layout).each(function (e, r) {
                    if (current_block = srv[l].block_params[r], "object" == typeof current_block) {
                        if ("page_break" !== current_block.typeName) 1 == current_block.properties.bottomScreen ? (d = jQuery(srv.generateHTML(current_block.typeName, l)), current_block.properties.hide_on_init && srv[l].hide_on_init.push(current_block.typeName + "_" + current_block.id)) : ($block = jQuery(srv.generateHTML(current_block.typeName, l)), current_block.properties.hide_on_init && srv[l].hide_on_init.push(current_block.typeName + "_" + current_block.id), $page.append($block)); else {
                            srv[l].page_count++;
                            var t = current_block.properties,
                                a = srv[l].survey_properties.advanced.hideSubNavigation ? "none" : "inline-block",
                                s = srv.jQuery("<div>").addClass("form-actions");
                            if (s.append("<span>" + current_block.title + "</span>"), 1 != t.isFirst && "" !== srv.jQuery.trim(t.prevLabel) && (_ = srv.jQuery("<button>").attr({
                                id: "btn_prev_" + current_block.id,
                                style: "display:" + a
                            }).addClass("btn btn-previous pull-left").on("click", function () {
                                srv.prevPage(!1, l);
                            }).css(touch_css).html(t.prevLabel), s.append(_)), $next_btn = srv.jQuery("<button>").attr({
                                id: "btn_next_" + current_block.id,
                                style: "display:" + a
                            }).addClass("btn btn-next pull-right").on("click", function () {
                                srv.nextPage(!1, l);
                            }).css(touch_css).html(t.nextLabel), void 0 !== t.isAction && 1 == t.isAction && $next_btn.addClass("btn-primary btn-submit"), t.autopost) {
                                var o, n = $page[0].children.length, i = $page[0].children[n - 1].childNodes;
                                for (e = 0; e < i.length; e++) ("hidden" == (o = i[e]).type && null === o.id.match(/checkbox/gi) || null !== o.id.match(/select/gi)) && (s.addClass("autopost"), $node = srv.jQuery(o), $node.on("change.autopost", function () {
                                    srv.nextPage(!1, l);
                                    var e = srv[l].current_page, r = srv[l].current_page - 1;
                                    jQuery("#page" + r + " .form-actions").removeClass("autopost"), jQuery(this).off("change.autopost"), setTimeout(function () {
                                        0 !== jQuery(".alert.alert-danger").length && jQuery("#page" + e + " .form-actions").removeClass("autopost");
                                    });
                                }));
                            }
                            s.append($next_btn), $page.append(s), c.append($page), $page = srv.jQuery("<div>").attr("id", "page" + srv[l].page_count).addClass("page-break").css("display", "none");
                        }
                        updateQuestionCount(current_block, l);
                    }
                });
                var p = this.getLanguage("btnSubmitText", l),
                    f = srv[l].survey_properties.advanced.hideNavigation ? "none" : "inline-block",
                    y = srv.jQuery("<button>").attr({
                        id: "surveySubmitBtn",
                        style: "display:" + f
                    }).addClass("btn btn-primary btn-submit pull-right").on("click", function () {
                        srv.submitSurvey(l);
                    }).css(touch_css).html(p), m = srv.jQuery("<div>").addClass("form-actions");
                if (1 < this[l].page_count) {
                    var h = this.getLanguage("btnLastBackText", l),
                        _ = srv.jQuery("<button>").addClass("btn btn-previous").attr("style", "display:" + f).on("click", function () {
                            srv.prevPage(!1, l);
                        }).html(h);
                    m.append(_);
                }
                if (m.append(y), $page.append(m), c.append($page), this[l].survey_properties.advanced.showProgressbar) {
                    var g = srv.jQuery("<div>").attr("id", "progress").addClass("progress");
                    if (this[l].survey_properties.advanced.toggleDotview) {
                        g.addClass("dot-layout");
                        for (var b = this[l].page_count, j = 1; j <= b; j++) {
                            var k = 1 == j ? " active" : "", Q = j == b ? " last" : "", w = 100 / (b - 1),
                                C = jQuery("<div>").addClass("progress-dot").text(j);
                            jQuery("<div>").addClass("progress-wrap" + k + Q).css("width", w + "%").attr("data-pagecount", j).append(C).appendTo(g);
                        }
                        jQuery('.progress_dot[data-pagecount="1"]').addClass("active");
                    } else g.addClass("bar-layout").append('<div id="progressBar" class="bar" style="width: 0%;"></div>');
                }
                var S = srv.jQuery("<div>").append(s).append(g).append(c).append(n).append(o).css("opacity", "0");
                srv[l].outer_modal ? S.addClass("is-embed") : "slide" == srv[l].slider.type ? S.addClass("mopinion-slide") : S.addClass("is-modal"), S.attr("data-key", l).addClass("mopinion-survey-output " + themeClass), d && S.append(d), srv[l].initBlockRules(), srv[l].initSurveyRules(), srv[l].outer_modal || resizeListener("surveyContent", l), a = !0;
            } else if (this[l].outer_modal) {
                if ("slide" !== srv[l].slider.type && srv[l].show_button && (S = srv.showButton(l)), isPreview && (srv.jQuery("#surveyPreviewButtonValue").html(!srv[l].button || srv[l].button.hide ? "Yes" : "No"), !srv[l].button || srv[l].button.hide)) {
                    var x = srv.jQuery("<button>").addClass("force-modal-btn").html("Force open").css(touch_css).on("click", function () {
                        srv.openModal(!0, !1);
                    });
                    srv.jQuery("#surveyPreviewButtonValue").append(x);
                }
            } else S = srv.jQuery("<div>").addClass("alert alert-error").html("Error loading survey");
            for (var T in t.append(S), srv[l].survey_text.errors) srv[l].error_messages[T] = srv[l].survey_text.errors[T];
            for (var j in srv.jQuery("input[type=text], textarea").keyup(function () {
                enableNext(l);
            }), srv.jQuery("input[type=checkbox], input[type=radio]").click(function () {
                enableNext(l);
            }), srv.jQuery("input[type=text], textarea").blur(function () {
                showError(srv.jQuery(this), __MS.validateField(this));
            }), srv.jQuery("select").change(function () {
                showError(srv.jQuery(this), __MS.validateField(this)), enableNext(l);
            }), void 0 !== srv[l].survey_properties && "disable" == srv[l].survey_properties.advanced.next_button_behaviour ? srv.jQuery(".btn-next, #surveySubmitBtn").prop("disabled", !0) : void 0 !== srv[l].survey_properties && "hide" == srv[l].survey_properties.advanced.next_button_behaviour && srv.jQuery(".btn-next, #surveySubmitBtn").hide(), srv[l].hide_on_init) hideBlock(srv[l].hide_on_init[j], !1, !1, l);
            if (initPostMessage(l), srv.getMeta({key: l}), isPreview && "-" == srv.jQuery("#surveyPreviewMessageContent").html() && srv.jQuery("#surveyPreviewMessageContent").html("All good!"), srv[l].outer_modal || __MS.hammerTime(), t.find("select").length) {
                var M = t.find("select");
                srv.niceSelect(M, !1, l), M.each(function (e, r) {
                    srv.jQuery(r);
                });
            }
            if (setTimeout(function () {
                enableNext(l);
            }, 500), S && setTimeout(function () {
                S.animate({opacity: 1}, {
                    duration: 225, display: "block", complete: function () {
                        srv.jQuery("#branding").addClass("anim").attr("style", "display:block!important;text-align:center;");
                    }
                });
            }), srv[l].is_loading = !1, "slide" === srv[l].slider.type && (srv[l].outer_modal && srv.preloadSlider(l, !1), srv[l].outer_modal || t.closest("#surveyBody").addClass("mopinion-slide")), a && srv[l].outer_modal) {
                EventInitializer("form_loaded");
                var E = "";
                try {
                    E = srv[l].survey_properties.name;
                } catch (T) {
                }
                srv.triggerEvent({event: "loaded", key: l, formName: E}), srv.triggerEvent({
                    event: "ready",
                    key: l,
                    formName: E
                });
            } else if (a && !srv[l].outer_modal) {
                E = "";
                try {
                    E = srv[l].survey_properties.name;
                } catch (T) {
                }
                srv.triggerEvent({event: "ready", key: l, formName: E}), srv.callParentWindow({
                    fn: "srv.triggerEvent",
                    data: {event: "ready", key: l, formName: E}
                });
            }
            if (!srv[l].outer_modal && void 0 !== srv[l].survey_properties && srv[l].survey_properties.advanced.hasOwnProperty("analytics_integration") && setTimeout(function () {
                addAnalytics(srv[l].survey_properties.advanced.analytics_integration, l);
            }, 500), PUBLIC_LANDING) landingIframe(!!srv[l].survey_properties.advanced.enableCustomBackground, !!srv[l].survey_properties.advanced.landingspageTransparency, srv[l].survey_properties.advanced.landingspageTheme ? srv[l].survey_properties.advanced.landingspageTheme : "dark", !!srv[l].survey_properties.advanced.landingspageClose && srv[l].survey_properties.advanced.landingspageClose);
        }
    }

    function EventHandler(e, r) {
        srv.eventsHandlers[e].push(r);
    }

    function EventInitializer(e) {
        for (var r in srv.eventsHandlers[e]) {
            var t = srv.eventsHandlers[e][r];
            "function" == typeof t && setTimeout(t, 350);
        }
    }

    function triggerEvent(e) {
        var r = e.element || document;
        if ("function" == typeof CustomEvent) {
            var t = new CustomEvent("mopinion_" + e.event, {bubbles: !1, detail: e});
            r.dispatchEvent(t);
        } else {
            (t = document.createEvent("CustomEvent")).initCustomEvent("mopinion_" + e.event, !1, !1, e), r.dispatchEvent(t);
        }
    }

    function landingIframe(e, r, t, a) {
        var s = getParameterByName("mail_url", LOCATION_REF);
        if (s && e && (null !== s.match(/^https?:\/\/[a-zA-Z0-9]+\.[a-zA-Z]+/gi) || null !== s.match(/^https?:\/\/www\.[a-zA-Z0-9]+\.[a-zA-Z]+/gi) || null !== s.match(/^https?:\/\/[a-zA-Z]+\.[a-zA-Z0-9]+\.[a-zA-Z]+/gi))) {
            var o = jQuery("<iframe>").attr({src: s, id: "mailUrl"}).addClass("bg__mailing_Iframe"),
                n = jQuery("<a>").addClass("close_custom_bg").addClass(t).attr({href: "javascript:;"}).on("click", function () {
                    jQuery("#surveyWrap").remove(), jQuery(this).remove(), jQuery("#mailUrl").css("z-index", "1001"), jQuery("html,body").css("overflow", "hidden"), jQuery("#publicSurveyBody").removeClass("dark light transparent");
                });
            if (a) jQuery("<span>").addClass("close_custom_bg_lbl").addClass(t).html(a).appendTo(n);
            jQuery("#publicSurveyBody").addClass("customBackground").addClass(t).append(o, n), r && jQuery("#publicSurveyBody").addClass("transparent");
        } else s && e && window.console && console.log("The given url seems to be incorrect: " + s);
    }

    function appendScript(e, r, t, a, s) {
        var o = document.getElementsByTagName("head")[0], n = document.getElementById(r);
        n && o.removeChild(n);
        var i = document.createElement("script");
        if (i.type = "text/javascript", i.id = r, i.src = e, t) for (var l in t) i[l] = t[l];
        if (a) var d = window.setInterval(function () {
            srv.log("waiting for " + a + " to load"), "function" == typeof window[a] && (srv.log(a + " loaded"), !0, window.clearInterval(d), o.appendChild(i));
        }, 10); else o.appendChild(i);
        if ("function" == typeof s) {
            var c = !1;
            i.onload = i.onreadystatechange = function () {
                if (!(c || this.readyState && "loaded" !== this.readyState && "complete" !== this.readyState)) {
                    c = !0;
                    try {
                        s();
                    } catch (e) {
                    }
                }
            };
        }
    }

    function appendStyle(e, r) {
        var t = document.getElementsByTagName("head")[0];
        if (!document.getElementById(r)) {
            var a = document.createElement("link");
            a.type = "text/css", a.rel = "stylesheet", a.id = r, a.href = e, t.appendChild(a);
        }
    }

    function log(e, r, t) {
        if (t && "log" != t) {
            if ("warn" == t) {
                if (this.show_log || !0 === r) try {
                    console.warn(e);
                } catch (e) {
                }
            } else if ("info" == t) {
                if (this.show_log || !0 === r) try {
                    console.info(e);
                } catch (e) {
                }
            } else if ("error" == t) throw new Error(e);
        } else if (this.show_log || !0 === r) try {
            console.log(e);
        } catch (e) {
        }
    }

    function prevPage(e, r) {
        srv.log("page_nr " + e);
        var t = srv.jQuery('[data-key="' + r + '"]');
        this[r].page_history[this[r].current_page] ? (srv.log("getting page from history"), this[r].prev_page = this[r].page_history[this[r].current_page]) : srv.log("natural flow"), $curr_page_div = t.find("#page" + this[r].current_page), $new_page_div = t.find("#page" + this[r].prev_page), this[r].next_page = this[r].current_page, $new_page_div.css("display", "block"), $curr_page_div.css("display", "none"), this[r].current_page = this[r].prev_page, this[r].prev_page--, updateProgress(r), updateHeader(r), scrollToSurveyElement(0, r);
    }

    function nextPage(e, r) {
        if (srv[r].lock_submit) return !1;
        var t = srv[r].page_map[this[r].current_page], a = __MS.validateAll(t, showError),
            s = srv.jQuery('[data-key="' + r + '"]');
        if (a) {
            if (srv[r].form_completion_ratio = Math.round(srv[r].current_page / srv[r].page_count * 100), scroll_top = 0, this[r].prev_page = this[r].current_page, $prev_page_div = s.find("#page" + this[r].prev_page), this[r].next_page && (e = this[r].next_page, this[r].page_history[this[r].next_page] = this[r].current_page), e ? this[r].current_page = e : this[r].current_page++, -1 == e) s.find("#lastPage").css("display", "block").find(".completed-anim").addClass("do-anim"), s.find("#surveyTitle h1").text(this.getLanguage("lastPageTitle", r)), s.find("#surveyDescription").css("display", "none"), s.find("#lastPage").parent().find(".control-group").length && s.find("#lastPage").parent().find(".control-group").css("display", "none"); else {
                s.find("#page" + this[r].current_page).css("display", "block");
                this.send(!1, r);
            }
            $prev_page_div.css("display", "none"), this[r].next_page = !1;
        } else srv.log(srv[r].error_messages.deflt, !1, "warn"), first_error = srv.jQuery("#" + __MS.errors[0]).parent(), scroll_top = first_error.offset().top;
        updateProgress(r), updateHeader(r), scrollToSurveyElement(scroll_top, r), enableNext(r);
    }

    function updateQuestionCount(e, r) {
        "matrix" == e.typeName || "likert" == e.typeName ? srv[r].question_count = srv[r].question_count + Object.keys(e.properties.elements).length : "section_break" != e.typeName && "page_break" != e.typeName && srv[r].question_count++;
    }

    function updateHeader(e) {
        var r = srv.jQuery('[data-key="' + e + '"]');
        1 < srv[e].current_page || srv[e].current_page < 0 ? r.find("#surveyDescription").css("display", "none") : (r.find("#surveyTitle").css("display", "block"), r.find("#surveyDescription").css("display", "block"));
    }

    function updateProgress(e) {
        if (srv[e].outer_modal || srv.jQuery("html").removeClass(function (e, r) {
            return (r.match(/(^|\s)mopinion-onpage-\S+/g) || []).join(" ");
        }).addClass("mopinion-onpage-" + (-1 == srv[e].current_page ? "last" : srv[e].current_page)), -1 == srv[e].current_page) 100, srv.jQuery("#progress").remove(); else if (srv[e].survey_properties.advanced.toggleDotview) srv.jQuery(".progress-wrap").removeClass("active completed"), srv.jQuery('.progress-wrap[data-pagecount="' + srv[e].current_page + '"]').addClass("active"), srv.jQuery('.progress-wrap[data-pagecount="' + srv[e].current_page + '"]').prevAll().addClass("completed"); else {
            var r = srv[e].form_completion_ratio;
            srv.jQuery("#progress").children(".bar").css("width", r + "%");
        }
    }

    function setValue(e, r, t) {
        var a = srv.jQuery("#" + r);
        if ($id_nr = r.replace(e.type + "_", ""), "checkbox" == e.type) {
            $new_value = {};
            a.val().split(",");
            srv.jQuery("input[name=" + e.name + "]:checked").each(function (e, r) {
                var t = r.id.split("_"), a = t[t.length - 1];
                r.value && ($new_value["ch_" + a] = r.value);
            }), a.val(JSON.stringify($new_value)), "{}" == a.val() && a.val("");
        } else if (t) {
            var s = e.id.split("_"), o = s[s.length - 1];
            $new_value = {}, $new_value["rdo_" + o] = e.value, a.val(JSON.stringify($new_value));
        } else a.val(e.value);
        a.val(a.val()).trigger("change"), showError(a, {state: !0}), __MS.unsetError(a);
    }

    function send(a, s) {
        var o = srv.jQuery('[data-key="' + s + '"]');
        setLock(s);
        var n = function (e) {
            if (1 != srv[s].testMode) return e.code = 200, e.msg = "Everything is allright. No ...really its ok, believe me. (please...)", void t(e);
            var r = "Failed to send survey!";
            (e.verbose || !1) && (r += "\n\nCode:" + e.code + "\nMessage:" + e.error), srv.log(r, !0), clearLock(s), o.find("#surveySubmitBtn").removeClass("is-submitting").removeClass("btn-primary").addClass("btn-danger").html('<i class="fa fa-exclamation-circle"></i> Failed!, click to retry.').prop("disabled", !1);
        }, t = function (e) {
            if (srv.log("Api Response:\n Code:" + e.code + "\n" + e.msg, !1, "info"), srv[s].lock_submit = !1, clearLock(s), e && 200 == e.code) if (e.id && (srv[s].followUpId = e.id, srv[s].sendMode = "upsert"), a) {
                srv[s].outer_modal ? setCookies({type: "sent", key: s}) : srv.callParentWindow({
                    fn: "srv.setCookies",
                    data: {type: "sent", key: s}
                });
                var r = {timeout: srv[s].survey_properties.timeout};
                srv[s].survey_properties.notify && srv[s].survey_properties.notify.send && (notify = srv[s].survey_properties.notify, r.to = srv.jQuery("#" + srv[s].survey_properties.notification_email).val(), r.subject = notify.subject || "", r.sender = notify.sender || "", r.message = notify.message || "", o.find("#lastPage").html(srv[s].survey_properties.exit_content.replace("%email%", r.to)), o.find("#lastPage").prepend('<div class="completed-anim do-anim"><i class="fa fa-check"></i></div>')), srv[s].survey_properties.modal && o.find("#lastPage").append('<div id="autoClose" class="countdown muted" style="position:absolute; bottom:40px; right:20px;">window closes in <span id="countdown"></span> seconds</div>'), postSubmit(r, s);
                var t = {event: "feedback_sent", key: s, formName: srv[s].survey_properties.name};
                e.data && e.data.data && srv[s].survey_properties.advanced.return_feedback && (t.feedback = srv.jQuery.map(e.data.data, function (r) {
                    var e = Object.keys(srv[s].block_params).filter(function (e) {
                        return srv[s].block_params[e].import_var == r.label;
                    })[0], t = "";
                    try {
                        t = srv[s].block_params[e].title;
                    } catch (e) {
                    }
                    if ("image" !== r.type && "dom" !== r.type) return srv.jQuery.extend(r, {title: t});
                })), srv[s].outer_modal ? (srv.triggerEvent(t), srv.clearCaptureEvents(s)) : (srv.callParentWindow({
                    fn: "srv.triggerEvent",
                    data: t
                }), srv.callParentWindow({
                    fn: "srv.clearCaptureEvents",
                    data: s
                })), "slide" === srv[s].slider.type && setTimeout(function () {
                    srv.callParentWindow({fn: "srv.closeSlider", data: s}), setTimeout(location.reload(), 750);
                }, 5e3);
            } else {
                t = {event: "next", key: s, formName: srv[s].survey_properties.name};
                e.data && e.data.data && srv[s].survey_properties.advanced.return_feedback && (t.feedback = srv.jQuery.map(e.data.data, function (r) {
                    var e = Object.keys(srv[s].block_params).filter(function (e) {
                        return srv[s].block_params[e].import_var == r.label;
                    })[0], t = "";
                    try {
                        t = srv[s].block_params[e].title;
                    } catch (e) {
                    }
                    if ("image" !== r.type && "dom" !== r.type) return srv.jQuery.extend(r, {title: t});
                })), srv[s].outer_modal ? srv.triggerEvent(t) : srv.callParentWindow({fn: "srv.triggerEvent", data: t});
            } else n(e);
        }, i = {feedback: []};
        srv.jQuery(srv[s].survey_send_options.data).each(function (e, r) {
            if (r) {
                var t = r.by_name ? srv.jQuery('input[name="' + r.field + '"]:checked') : srv.jQuery("#" + r.field),
                    a = "checkbox" == t.attr("type") ? t.prop("checked") : t.val();
                i.feedback.push({label: r.label, value: a, type: r.type});
            }
        }), !1 !== srv[s].survey_send_options.force_customer && i.feedback.push({
            value: srv[s].survey_send_options.force_customer.value,
            type: "customer",
            label: srv[s].survey_send_options.force_customer.label
        }), i.feedback.push({value: navigator.userAgent, type: "agent", label: "User Agent"});
        var r = getParameterByName("parent", document.location), l = "";
        if (r) try {
            l = document.location.href.split("&parent=")[1].split("&cip=")[0];
        } catch (e) {
            l = r;
        } else l = LOCATION_REF;
        i.feedback.push({value: l, type: "url", label: "url"}), i.feedback.push({
            value: srv[s].document_title,
            type: "category",
            label: "Page title"
        }), i.feedback.push({
            value: 1,
            type: "role",
            label: "Role"
        }), i.feedback.push({
            value: srv[s].survey_properties.name,
            type: "category",
            label: "Survey"
        }), i.feedback.push({
            value: srv[s].window_viewport,
            type: "viewport",
            label: "Viewport"
        }), srv[s].trigger_method && i.feedback.push({
            value: srv[s].trigger_method,
            type: "category",
            label: "Form trigger"
        }), i.feedback.push({
            value: srv[s].form_completion_ratio,
            type: "form_completion",
            label: "Form completion percentage"
        }), "insert" !== srv[s].sendMode && i.feedback.push({value: srv[s].followUpId, type: "id", label: "Survey ID"});
        var e = {
            token: srv[s].survey_send_options.token,
            domain: srv[s].survey_send_options.domain,
            surveyId: srv[s].survey_properties.id,
            ip: srv[s].cip || 0,
            data: i,
            mode: srv[s].sendMode
        };
        srv[s].survey_send_options.preview ? (srv.log("Preview mode, not sending"), clearLock(s)) : this.request("send", e, t, n, !1, s);
    }

    function request(e, r, t, a, s, o) {
        a || (a = function () {
            srv.log("Error processing request", !0, "warn");
        });
        var n = "https://" + srv[o].domain;
        srv.jQuery.ajax({
            async: !0,
            url: n + "/survey/public/" + e,
            dataType: "json",
            type: "POST",
            data: r,
            error: a,
            success: t
        });
    }

    function submitSurvey(e) {
        if (srv[e].lock_submit) return !1;
        var r = srv.jQuery('[data-key="' + e + '"]');
        r.find("#surveySubmitBtn").prop("disabled", !0);
        var t = srv[e].page_map[this[e].current_page];
        __MS.validateAll(t, showError) ? (r.find("#surveySubmitBtn").data("buttonContent", r.find("#surveySubmitBtn").html()).html('<i class="fa fa-refresh"></i>').addClass("btn-primary").removeClass("btn-danger").addClass("is-submitting"), srv[e].form_completion_ratio = Math.round(srv[e].current_page / srv[e].page_count * 100), this.send(!0, e)) : (srv.log(srv[e].error_messages.deflt, !1, "warn"), r.find("#surveySubmitBtn").prop("disabled", !1), first_error = srv.jQuery("#" + __MS.errors[0]).parent(), scroll_top = first_error.offset().top, scrollToSurveyElement(scroll_top, e));
    }

    function openModal(e, r) {
        if (!r) {
            if (1 !== ALL_KEYS.length) {
                try {
                    srv.log("Key needed when multiple forms are initialized", !0, "warn");
                } catch (e) {
                }
                return;
            }
            r = ALL_KEYS[0];
        }
        if (-1 != ALL_KEYS.indexOf(r)) {
            if ("slide" !== srv[r].slider.type) {
                if (getCookie("MSopened") == r && !e || srv[r].modal_open) {
                    var t = "Not opening, reason: ";
                    srv[r].modal_open ? t += "Already open" : getCookie("MSopened") == r && (t += "Got a cookie, and not forced by user"), isPreview && srv.jQuery("#surveyPreviewMessageContent").html(t), srv.log(t);
                } else {
                    e && srv.log("User forced opening of modal", !1, "info"), srv[r].modalFirst = !0;
                    var a = srv.jQuery("#" + srv[r].div_name), s = srv.jQuery("<div>").attr("id", "surveyMask"),
                        o = srv.jQuery("<div>").attr("id", "surveyModalLoader").addClass("survey-modal-loader").append('<svg class="circular-spin" viewBox="25 25 50 50"><circle class="stroke-path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg>'),
                        n = "https://" + srv[r].domain + "/survey/public/modal?&key=" + r + "&domain=" + srv[r].domain + "&version=" + srv[r].version + "&parent=" + document.location,
                        i = srv.jQuery("<div>").attr({
                            id: "surveyWindowWrap",
                            "data-parent-for": r
                        }).addClass("surveyWindowWrap").css({
                            position: "fixed",
                            top: 0,
                            left: 0,
                            right: 0,
                            "z-index": 2000000001,
                            bottom: 0,
                            "overflow-y": "auto",
                            "-webkit-overflow-scrolling": "touch",
                            "backface-visibility": "hidden",
                            width: "100%",
                            "min-height": "100%"
                        }).on("click", function () {
                            s.click();
                        }),
                        l = srv.jQuery("<iframe>").attr("id", "surveyWindow").attr("data-key", r).attr("src", n).attr("frameBorder", !1).attr("allowTransparency", !0).addClass("surveyWindow").css({
                            border: "none",
                            "border-radius": "2px",
                            position: "absolute",
                            top: "50px",
                            left: 0,
                            right: 0,
                            "margin-left": "auto",
                            "margin-right": "auto",
                            "max-width": "480px",
                            "z-index": 2000000001,
                            "margin-bottom": 50,
                            "box-shadow": "0 8px 17px 0 rgba(0,0,0,0.2)",
                            opacity: 0
                        }).appendTo(i);
                    handleIframeWidth(l), a.append(s).append(o), s.animate({opacity: .15}, {
                        complete: function () {
                            srv.jQuery("html,body").addClass("mopinion-modal-active"), a.append(i);
                            var e = "";
                            try {
                                e = srv[r].outerProperties.name;
                            } catch (e) {
                            }
                            srv.triggerEvent({event: "shown", key: r, formName: e}), s.on("click", function () {
                                srv.closeModal(r);
                            }), srv[r].modal_open = !0, setCookies({type: "open", key: r}), srv.hideLoader();
                        }
                    }), srv.jQuery(window).on("resize orientationchange", function () {
                        handleIframeWidth(l);
                    });
                }
                return !0;
            }
            srv.openSlider(e, r);
        } else srv.open({key: r, show_button: !1, trigger_method: "force"});
    }

    function closeModal(r) {
        if ("slide" !== srv[r].slider.type) {
            var t = srv.jQuery("#surveyMask"), e = srv.jQuery('[data-key="' + r + '"]'),
                a = e.closest(".surveyWindowWrap");
            srv.clearCaptureEvents(r), e.animate({opacity: 0}, {
                duration: 300, complete: function () {
                    srv[r].modalFirst = !1, a.remove(), t.animate({opacity: 0}, {
                        duration: 300, complete: function () {
                            t.remove(), srv[r].modal_dismissed = !0, srv[r].modal_open = !1, srv.hideLoader(), srv.jQuery("html,body").removeClass("mopinion-modal-active");
                            var e = "";
                            try {
                                e = srv[r].outerProperties.name;
                            } catch (e) {
                            }
                            srv.triggerEvent({event: "hidden", key: r, formName: e});
                        }
                    });
                }
            });
        } else srv.closeSlider(r);
    }

    function hideLoader() {
        srv.jQuery("#surveyModalLoader").fadeOut(500).promise().done(function () {
            srv.jQuery(this).remove();
        });
    }

    function setCookies(e) {
        2 == srv.cookie_level ? path = document.location.href : 1 == srv.cookie_level ? path = document.location.pathname : path = "/";
        var r = e.key, t = e.type, a = srv[r].cookie_expire || 365;
        "open" === t ? (setCookie("MSopened", r, a, path, void 0, !1), setCookie("MSopened." + r, !0, a, path, void 0, !1)) : "sent" === t && (setCookie("MSFeedbackSent", r, a, path, void 0, !1), setCookie("MSFeedbackSent." + r, !0, a, path, void 0, !1));
    }

    function setCookie(e, r, t, a, s, o) {
        var n = new Date;
        n.setTime(n.getTime()), t && (t = 1e3 * t * 60 * 60 * 24);
        var i = new Date(n.getTime() + t);
        document.cookie = e + "=" + escape(r) + (t ? ";expires=" + i.toGMTString() : "") + (a ? ";path=" + a : "") + (s ? ";domain=" + s : "") + (o ? ";secure" : "");
    }

    function getCookie(e) {
        var r, t, a, s = document.cookie.split(";");
        for (r = 0; r < s.length; r++) if (t = s[r].substr(0, s[r].indexOf("=")), a = s[r].substr(s[r].indexOf("=") + 1), (t = t.replace(/^\s+|\s+$/g, "")) == e) return unescape(a);
    }

    function handleIframeWidth(e) {
        var r, t = srv.jQuery(window).width();
        (e = !(!__MS.isDOM(e) && !__MS.isDOM(e[0])) && e) && (1200 < t ? r = "40%" : 992 < t && t < 1201 ? r = "45%" : t < 993 && 768 < t ? r = "50%" : t < 769 && 544 < t ? r = "70%" : t < 545 && (r = "90%"), e.css({width: r}));
    }

    function resizeListener(r, t) {
        var a = function (e) {
            if ("number" == typeof e && srv[t]) {
                srv[t].form_height = e;
                try {
                    srv.callParentWindow({fn: "srv.resizeForm", data: {height: e, key: t}});
                } catch (e) {
                }
            }
        };
        srv.jQuery(window).on("orientationchange", function () {
            setTimeout(function () {
                var e = document.getElementById(r).parentNode.offsetHeight;
                a(e);
            });
        });
        var s = setInterval(function () {
            if (document.getElementById(r)) {
                var e = document.getElementById(r).parentNode.offsetHeight;
                e !== srv[t].form_height && a(e);
            } else clearInterval(s);
        }, 10);
    }

    function callParentWindow(e, r) {
        r || (r = srv.getParameterByName("parent", document.location.href) || "*"), "function" == typeof window.postMessage && (isIE && (e = JSON.stringify(e)), parent.postMessage(e, r));
    }

    function callChildWindow(e, r) {
        var t, a;
        a = r ? (t = document.querySelector('[data-key="' + r + '"]')).getAttribute("src") : (t = document.getElementById("surveyWindow")).getAttribute("src"), "function" == typeof window.postMessage && t && "IFRAME" === t.nodeName && (isIE && (e = JSON.stringify(e)), t.contentWindow.postMessage(e, a));
    }

    function initPostMessage(key) {
        var url_match;
        if (PM_INIT) return !0;
        if (srv[key].outer_modal) {
            var url_match = "https://" + srv[key].domain;
            PM_INIT = !0;
        } else {
            var url_match = srv.getParameterByName("parent", document.location.href).split("/");
            3 < url_match.length && (url_match = url_match[0] + "//" + url_match[2]);
        }
        var receiveMessage = function (event) {
            if (event.data && event.origin === url_match) if ("object" == typeof event.data) {
                if (event.data.fn) {
                    var fn = eval(event.data.fn), data = event.data.data;
                    "function" == typeof fn && fn(data);
                }
            } else try {
                var parsed = JSON.parse(event.data);
                if (parsed.fn) {
                    var fn = eval(parsed.fn), data = parsed.data;
                    "function" == typeof fn && fn(data);
                }
            } catch (e) {
                var fn = eval(event.data.fn), data = event.data.data;
                "function" != typeof fn || "string" != typeof data && void 0 !== data || fn(data);
            }
        };
        void 0 !== window.addEventListener ? window.addEventListener("message", receiveMessage, !1) : window.attachEvent("onmessage", receiveMessage);
    }

    var resizeForm = debounce(function (e) {
        var r = e.key;
        if (r) {
            var t = srv.jQuery('[data-key="' + r + '"]'), a = t.closest(".surveySliderScroller");
            if (e.update) {
                if (1 == e.update) s = t.outerHeight() + 1;
            } else var s = parseInt(e.height, 10) + 1;
            var o = function () {
                return .9 * srv.jQuery(window).height() < s;
            };
            srv[r] && srv[r].modalFirst ? (a.length && o() ? a.css("overflow-y", "scroll") : a.length && a.css("overflow-y", "hidden"), t.stop().animate({
                opacity: 1,
                height: s
            }, {
                duration: 225, complete: function () {
                    srv[r].modalFirst = !1;
                }
            }), srv[r].form_height = s) : srv[r] && e.height && !srv[r].modalFirst && (a.length && o() ? a.css("overflow-y", "scroll") : a.length && a.css("overflow-y", "hidden"), t.stop().animate({height: s}, {
                duration: 225,
                complete: function () {
                    srv.log("Srv height set", !1, "info");
                }
            }), srv[r].form_height = s);
        }
    }, 100);

    function getLanguage(e, r) {
        return void 0 !== srv[r].survey_text && void 0 !== srv[r].survey_text[e] ? srv[r].survey_text[e] : "[" + e + "]";
    }

    function addButton(e, r) {
        var t = srv[e].button, a = t.content || srv.getLanguage("btnOpenText", e), s = t.style || "tab",
            o = t.position || "right", n = srv.jQuery("<i>").addClass("fa " + t.icon).css({marginRight: "10px"}) || "",
            i = srv[e].theme_class || "", l = "" != t.icon ? "" : "no_btn_icon";
        return "slide" !== srv[e].slider.type ? srv.jQuery("<button>").addClass("btn-open-survey btn btn-primary").addClass(s + " " + s + "-" + o + " " + i + " " + l).on("click", function () {
            srv.openModal(!0, e);
        }).css("visibility", "hidden").css(touch_css).attr("data-button-for", e).html(a).prepend(n) : srv.jQuery("<button>").attr({
            type: "button",
            id: "openSliderButton",
            "data-button-for": e
        }).addClass("btn btn-open-slider " + i).html('<i class="fa ' + t.icon + '"></i>').on("click", function () {
            srv[e].modal_open ? srv.closeSlider(e) : srv.openSlider(!0, e);
        });
    }

    function showButton(e) {
        var r = srv.jQuery("#" + srv[e].div_name);
        if (e && !r.find('[data-button-for="' + e + '"]').length) {
            srv.log("Adding feedback button", !1, "info");
            var t = this.addButton(e);
            "slide" !== srv[e].slider.type ? r.append(t) : r.find('[data-button-for="' + e + '"]').length || r.find('[data-key="' + e + '"]').length ? r.find('[data-key="' + e + '"]').length && srv.jQuery('[data-key="' + e + '"]').closest(".surveyWindowWrap").append(t) : srv.preloadSlider(e, !1, !0), setTimeout(function () {
                t.addClass("allow-button");
            }, 300);
        }
    }

    function removeButton(e) {
        srv.jQuery("#" + srv[e].div_name + ' [data-button-for="' + e + '"]').remove();
    }

    function isActive(e, r) {
        var t = "select" === r.typeName ? r.properties.selected : r.properties.checked;
        for (var a in t) if ("function" != typeof t[a] && "function" != typeof t[a] && t[a] == e) return !0;
        return !1;
    }

    function debounce(a, s, o) {
        var n;
        return function () {
            var e = this, r = arguments, t = o && !n;
            clearTimeout(n), n = setTimeout(function () {
                n = null, o || a.apply(e, r);
            }, s), t && a.apply(e, r);
        };
    }

    function cleanArray(e) {
        var r = [];
        for (var t in e) "function" != typeof e[t] && void 0 !== e[t] && r.push(e[t]);
        return r;
    }

    function rand() {
        return ("00000" + (16777216 * Math.random() << 0).toString(16)).substr(-6).toUpperCase();
    }

    function unsetRequirement(e, r) {
        var t = srv.jQuery("#" + e);
        t.hasClass("req") && (t.removeClass("error"), t.parent().removeClass("error"), -1 == srv[r].required_when_visible.indexOf(e) && srv[r].required_when_visible.push(e), delete __MS.errors[__MS.errors.indexOf(e)], __MS.errors = cleanArray(__MS.errors), t.removeClass("req"));
    }

    function setRequirement(e, r) {
        var t = srv.jQuery("#" + e);
        -1 < srv[r].required_when_visible.indexOf(e) && t.addClass("req");
    }

    function unhideBlock(r, t) {
        if (-1 < r.indexOf("contact_") ? ($initial_block = srv.jQuery('*[id^="' + r + '"]'), $block = $initial_block.closest(".contact-wrapper"), $block_elements = jQuery('*[id^="contact_' + $block.parent().attr("id") + '"]')) : ((-1 < r.indexOf("matrix_") || -1 < r.indexOf("likert_")) && ($block_elements = srv.jQuery('input[type="hidden"][id^="' + r + '"]')), $block = srv.jQuery("#" + r)), $block) {
            if (-1 < r.indexOf("section_break")) return $block = srv.jQuery("#" + r.split("_")[2]), void $block.css("display", "block");
            $block.hasClass("contact-wrapper") || $block.hasClass("matrix-group") || $block.hasClass("likert-group") ? $block_elements.each(function (e, r) {
                setRequirement(r.id, t);
            }) : setRequirement(r, t);
            try {
                $block.parent().css("display", "block");
            } catch (e) {
                srv.log("block:" + r + " not found");
            }
        }
    }

    function hideBlock(e, a, s, o) {
        if ("function" != typeof e && (-1 < e.indexOf("contact_") ? ($initial_block = srv.jQuery('*[id^="' + e + '"]'), $block = $initial_block.closest(".contact-wrapper"), $block_elements = jQuery('*[id^="contact_' + $block.parent().attr("id") + '"]')) : ((-1 < e.indexOf("matrix_") || -1 < e.indexOf("likert_")) && ($block_elements = srv.jQuery('input[type="hidden"][id^="' + e + '"]')), $block = srv.jQuery("#" + e)), $block)) if (-1 < e.indexOf("section_break")) $block = srv.jQuery("#" + e.split("_")[2]), $block.css("display", "none"); else {
            var r = $block.parent();
            r.css("display", "none"), r.removeClass("error"), $block.removeClass("error"), $block.hasClass("contact-wrapper") || $block.hasClass("matrix-group") || $block.hasClass("likert-group") ? $block_elements.each(function (e, r) {
                if (unsetRequirement(r.id, o), a) {
                    var t = srv.jQuery(r);
                    t.not(":checkbox,:radio").val(""), t.prop("checked", !1), t.is("select") && srv.niceSelect(t, "update", o), s && t.trigger(s);
                }
            }) : (unsetRequirement(e, o), a && (r.find("input").not(":checkbox,:radio").val(""), r.find("input").prop("checked", !1), r.find(".ui-stars-star").removeClass("ui-stars-star-on"), "select" == e.split("_")[0] && ($block.val(""), srv.niceSelect($block, "update", o)), s && $block.trigger(s)));
        }
    }

    function setPrefill(r, t, e, a) {
        var s;
        ($block = srv.jQuery("#" + r), $block) && (null !== r.match(/section_break/gi) && (r = r.replace("section_break", "section_description")), "url_query" == e ? s = getParameterByName(t, LOCATION_REF) : "cookie" == e ? s = getCookie(t) : "url" == e ? (-1 < document.referrer.indexOf(t) || -1 < LOCATION_REF.indexOf(t)) && ("show" === a ? srv.jQuery("#" + r).closest(".control-group").show() : srv.jQuery("#" + r).closest(".control-group").hide()) : "title" == e ? setTimeout(function () {
            var e = jQuery("[data-key]").attr("data-key");
            -1 < srv[e].document_title.indexOf(t) && ("show" === a ? srv.jQuery("#" + r).closest(".control-group").show() : srv.jQuery("#" + r).closest(".control-group").hide());
        }) : e && "fixed" != e || (s = key), $block.val(decodeURIComponent(s)), "" !== $block.val() && $block.trigger("blur"));
    }

    function setNextPage(e, r) {
        for (var t in srv[r].next_page = parseInt(e), srv[r].page_history) t == e || srv[r].page_history[t] == srv[r].current_page && delete srv[r].page_history[t];
    }

    function showError(e, r) {
        e instanceof jQuery == 0 && (e = srv.jQuery(e));
        var t = e.closest("[data-key]").attr("data-key"), a = e.parent(), s = e.attr("id");
        if ("TR" == a[0].nodeName) {
            var o = s.split("_"), n = o[0] + "_" + o[1];
            a = srv.jQuery("#" + n), s = n;
            r.code = "required_multi", __MS.unsetError(e), r.state ? __MS.unsetError(a) : __MS.setError(a);
        } else if (e.parents(".contact-wrapper")[0]) a = e.parent();
        if (srv.jQuery("#" + s + "_error")[0] && srv.jQuery("#" + s + "_error").remove(), !r.state && -1 != __MS.errors.indexOf(s)) {
            var i = srv.jQuery("<div>").attr("id", s + "_error").addClass("alert alert-danger").html(r.code && srv[t].error_messages[r.code] ? srv[t].error_messages[r.code] : srv[t].error_messages.deflt);
            a.append(i);
        }
    }

    function postSubmit(e, r) {
        srv.nextPage(-1, r);
        getParameterByName("parent", document.location.href);
        if (e.to) {
            var t = {surveyKey: r, email: e.to};
            srv.request("notify-user", t, function (e) {
                srv.log(e);
            }, function () {
                srv.log("Failed to send notification", !0, "error");
            }, !0, r);
        }
        var a = srv.jQuery('[data-key="' + r + '"]');
        if (0 < e.timeout && !srv.jQuery(window.frameElement).hasClass("preview_embed")) {
            var s = e.timeout;
            a.find("#countdown").html(e.timeout / 1e3);
            var o = setInterval(function () {
                s -= 1e3, a.find("#countdown").html(s / 1e3), s <= 0 && (clearInterval(o), srv.callParentWindow({
                    fn: "srv.closeModal",
                    data: r
                }));
            }, 1e3);
        } else a.find("#autoClose").remove();
    }

    function getParameterByName(e, r) {
        e = e.replace(/[\[]/, "[").replace(/[\]]/, "]");
        var t = new RegExp("[?&]" + e + "=([^&#]*)").exec(r);
        return null == t ? "" : decodeURIComponent(t[1].replace(/\+/g, " "));
    }

    function setLikert(e) {
        var r = srv.jQuery("#" + e.name);
        r.val(e.value), showError(r, {state: !0}), __MS.unsetError(r);
    }

    function setMatrix(e) {
        var r = e.name.split("_"),
            t = srv.jQuery('input[name="' + r[0] + "_" + r[1] + "_" + r[2] + '_value"]:checked').val(),
            a = srv.jQuery('input[name="' + r[0] + "_" + r[1] + "_" + r[2] + '_weight"]:checked').val(),
            s = srv.jQuery("#" + r[0] + "_" + r[1] + "_" + r[2]);
        if (t && a) {
            var o = JSON.stringify({value: t, weights: a});
            s.val(o), showError(s, {state: !0}), __MS.unsetError(s);
        }
    }

    function initCapture(l) {
        var i;
        if (!(l = l || {}).embedded) {
            srv.jQuery("html,body").removeClass("mopinion-modal-active");
            var e = srv.jQuery('[data-key="' + l.key + '"]');
            e.addClass("screen-capture-active"), e.closest(".surveyWindowWrap").addClass("screen-capture-active"), srv.jQuery("#surveyMask").addClass("screen-capture-active");
        }

        function d(e, r) {
            if (srv.jQuery("#mopinion_dimmer_overlay").remove(), e) var t = e[0].getBoundingClientRect();
            var a = srv.jQuery("<div>").attr({
                id: "mopinion_dimmer_overlay",
                "data-dimmer-for": l.key
            }).addClass(srv[l.key].theme_class || "");
            l.embedded && a.addClass("is-embedded");
            srv.jQuery("<div>").addClass("dimmer-border").appendTo(a);
            var s = srv.jQuery("<div>").addClass("dimmer-actions").appendTo(a);
            srv.jQuery("<div>").html(r ? '<i class="fa fa-check" style="margin-right:10px"></i>' + srv.getLanguage("screenCaptureSelected", l.key) : (user_touching ? '<i class="fa fa-hand-pointer-o" style="margin-right:10px"></i>' : '<i class="fa fa-mouse-pointer" style="margin-right:10px"></i>') + srv.getLanguage("screenCaptureText", l.key)).appendTo(s), srv.jQuery("<div>").html("&times;").addClass("dimmer-cancel").on("click", function (e) {
                e.stopPropagation(), setTimeout(function () {
                    srv.clearCaptureEvents(l.key), l.embedded ? srv.clearCaptureValues({
                        block_id: l.block_id,
                        key: l.key
                    }) : (srv.showModalPostCapture(l.key), srv.callChildWindow({
                        fn: "srv.clearCaptureValues",
                        data: {block_id: l.block_id, key: l.key}
                    }, l.key));
                });
            }).appendTo(s);
            if (e) {
                if (!l.embedded && !0 === l.supportsPointerEvents) srv.jQuery("<div>").css({
                    position: "fixed",
                    background: "rgba(0,0,0,.3)",
                    top: 0,
                    height: t.top,
                    left: 0,
                    right: 0,
                    pointerEvents: "none"
                }).appendTo(a), srv.jQuery("<div>").css({
                    position: "fixed",
                    background: "rgba(0,0,0,.3)",
                    top: t.bottom,
                    bottom: 0,
                    left: 0,
                    right: 0,
                    pointerEvents: "none"
                }).appendTo(a), srv.jQuery("<div>").css({
                    position: "fixed",
                    background: "rgba(0,0,0,.3)",
                    top: t.top,
                    height: t.height,
                    left: 0,
                    width: t.left,
                    pointerEvents: "none"
                }).appendTo(a), srv.jQuery("<div>").css({
                    position: "fixed",
                    background: "rgba(0,0,0,.3)",
                    top: t.top,
                    height: t.height,
                    width: srv.jQuery(window).width() - t.right,
                    right: 0,
                    pointerEvents: "none"
                }).appendTo(a);
                var o = srv.jQuery("<div>").addClass("dimmer-highlight").css({
                    position: "fixed",
                    top: t.top - 3,
                    left: t.left - 3,
                    height: t.height + 6,
                    width: t.width + 6
                }).appendTo(a);
                if (user_touching && !r) srv.jQuery("<button>").attr({type: "button"}).text(srv.getLanguage("screenCaptureTouchBtnText", l.key)).addClass("btn-select-touch").on("click", function (e) {
                    e.preventDefault(), e.stopPropagation(), srv.jQuery(this).trigger("click.mopinion_screencapture", {selected: !0});
                }).appendTo(o);
                r && o.addClass("dimmer-selected"), srv.jQuery("body").append(a);
            } else {
                srv.jQuery("<div>").css({
                    position: "fixed",
                    background: "rgba(0,0,0,.3)",
                    top: 0,
                    bottom: 0,
                    left: 0,
                    right: 0,
                    pointerEvents: "none"
                }).appendTo(a);
                var n = srv.jQuery("<div>").addClass("capture-hint").appendTo(a), i = srv.jQuery("<i>").appendTo(n);
                user_touching ? i.addClass("fa fa-hand-pointer-o") : i.addClass("fa fa-mouse-pointer");
                srv.jQuery("<div>").text(srv.getLanguage("screenCaptureHint", l.key)).appendTo(n);
                srv.jQuery("body").append(a);
            }
        }

        setTimeout(function () {
            srv.jQuery(document).on("mousemove.mopinion_screencapture", function (e) {
                user_touching || d(srv.jQuery(e.target));
            }), srv.jQuery("*").on("click.mopinion_screencapture", function (e, r) {
                if (e.preventDefault(), e.stopPropagation(), srv.jQuery(document).off("mousemove.mopinion_screencapture"), r || (i = e.target), srv.jQuery(window).off("scroll.mopinion_screencapture").on("scroll.mopinion_screencapture", function (e) {
                    !user_touching || r ? d(srv.jQuery(i), !0) : d(srv.jQuery(i));
                }), !user_touching || r && r.selected) {
                    srv.jQuery("#mopinion_dimmer_overlay").remove(), srv.jQuery("[data-mopinion-screen-capture]").removeAttr("data-mopinion-screen-capture");
                    var t = (s = i, o = srv.jQuery(s).clone(), n = ["[data-mop-supress]"], l.maskedSelectors.length && (n = n.concat(l.maskedSelectors)), n.forEach(function (e) {
                        if (o.is(e)) o.html("***"); else try {
                            o.find(e).html("***");
                        } catch (e) {
                        }
                    }), o.find("input").each(function (e, r) {
                        var t = srv.jQuery(r);
                        try {
                            t.attr("value", "***");
                        } catch (e) {
                        }
                        try {
                            t.attr("checked", !1);
                        } catch (e) {
                        }
                    }), o.find("select").each(function (e, r) {
                        var t = srv.jQuery(r);
                        try {
                            t.find("option:selected").removeAttr("selected");
                        } catch (e) {
                        }
                    }), o.find("textarea").each(function (e) {
                        srv.jQuery(e).text("***");
                    }), srv.jQuery.trim(o.prop("outerHTML")).replace(/(\r\n|\n|\r)/gm, " "));
                    srv.jQuery(i).attr("data-mopinion-screen-capture", "");
                    var a = function () {
                        var r = srv.jQuery("html").clone(), e = ["[data-mop-supress]"];
                        l.maskedSelectors.length && (e = e.concat(l.maskedSelectors)), r.find("input").each(function (e, r) {
                            var t = srv.jQuery(r);
                            try {
                                t.attr("value", "***");
                            } catch (e) {
                            }
                            try {
                                t.attr("checked", !1);
                            } catch (e) {
                            }
                            try {
                                t.attr("data-mopinion-mask-input", "");
                            } catch (e) {
                            }
                        }), r.find("select").each(function (e, r) {
                            var t = srv.jQuery(r);
                            try {
                                t.attr("data-mopinion-mask-input", "");
                            } catch (e) {
                            }
                            try {
                                t.find("option:selected").removeAttr("selected");
                            } catch (e) {
                            }
                        }), r.find("textarea").each(function (e) {
                            var r = srv.jQuery(e);
                            try {
                                r.text("***").attr("data-mopinion-mask-input");
                            } catch (e) {
                            }
                        }), e.forEach(function (e) {
                            try {
                                r.find(e).attr("data-mopinion-mask-extra", "");
                            } catch (e) {
                            }
                        });
                        var t = srv.jQuery("<base>").attr("href", location.origin);
                        if (r.find("head").find("base").length) {
                            if (r.find("head").find("base").length) {
                                var a = r.find("head").find("base").attr("href"), s = srv.jQuery("<base>");
                                if (-1 === a.indexOf(location.origin)) {
                                    var o = "/" === a.charAt(0) ? location.origin + a : location.origin + "/" + a;
                                    s.attr("href", o);
                                } else s.attr("href", a);
                                r.find("head").find("base").remove(), r.find("head").prepend(s);
                            }
                        } else r.find("head").prepend(t);
                        return srv.jQuery.trim(r.prop("outerHTML")).replace(/(\r\n|\n|\r)/gm, " ");
                    }();
                    d(srv.jQuery(i), !0), srv.jQuery("*").off("click.mopinion_screencapture"), l.embedded ? srv.catchCapture({
                        dom: a,
                        selector: t,
                        block_id: l.block_id,
                        key: l.key
                    }) : srv.callChildWindow({
                        fn: "srv.catchCapture",
                        data: {dom: a, selector: t, block_id: l.block_id, key: l.key}
                    }, l.key);
                } else d(srv.jQuery(i));
                var s, o, n;
            });
        }, 150), d();
    }

    function clearCaptureEvents(e) {
        setTimeout(function () {
            srv.jQuery("#mopinion_dimmer_overlay").remove(), srv.jQuery("*").off("click.mopinion_screencapture"), srv.jQuery(document).off("mousemove.mopinion_screencapture"), srv.jQuery(window).off("scroll.mopinion_screencapture"), srv.jQuery("[data-mopinion-screen-capture]").removeAttr("data-mopinion-screen-capture");
        });
    }

    function clearCaptureValues(e) {
        srv.jQuery("#screenshot_" + e.block_id).val("").trigger("change"), srv.jQuery("#dom_" + e.block_id).val(""), srv.jQuery("#selector_" + e.block_id).val(""), srv.jQuery("#" + e.block_id).removeClass("screen-captured"), __MS.validateField(srv.jQuery("#screenshot_" + e.block_id)[0]);
    }

    function toHTMLEntities(e) {
        return e.replace(/./gm, function (e) {
            return "&#" + e.charCodeAt(0) + ";";
        });
    }

    function fromHTMLEntities(e) {
        return (e + "").replace(/&#\d+;/gm, function (e) {
            return String.fromCharCode(e.match(/\d+/gm)[0]);
        });
    }

    function catchCapture(e) {
        e = e || {}, srv.log("Screen capture caught for block :" + e.block_id);
        var r = fromHTMLEntities(e.selector), t = encodeQuotes(e.dom);
        srv.jQuery("#selector_" + e.block_id).val(r), srv.jQuery("#dom_" + e.block_id).val(t), srv.jQuery("#screenshot_" + e.block_id).val("https://mopinion-visual-feedback.s3-eu-west-1.amazonaws.com/camera-icon.png").trigger("change"), __MS.validateField(srv.jQuery("#screenshot_" + e.block_id)[0]), e.embedded || srv.callParentWindow({
            fn: "srv.showModalPostCapture",
            data: e.key
        }), 0 < r.length && 0 < t.length ? srv.jQuery("#" + e.block_id).addClass("screen-captured") : srv.jQuery("#" + e.block_id).removeClass("screen-captured");
    }

    function showModalPostCapture(e) {
        "slide" !== srv[e].slider.type ? srv.jQuery("html,body").addClass("mopinion-modal-active") : srv.jQuery("html,body").addClass("mopinion-slider-active");
        var r = srv.jQuery('[data-key="' + e + '"]');
        r.removeClass("screen-capture-active"), r.closest(".surveyWindowWrap").removeClass("screen-capture-active"), srv.jQuery("#surveyMask").removeClass("screen-capture-active");
    }

    function userIdle(r, t) {
        idleTimer = null, idleState = !1, srv.jQuery("*").bind("mousemove keydown scroll", function (e) {
            clearTimeout(idleTimer), idleState, idleState = !1, idleTimer = setTimeout(function () {
                srv.log("You've been idle for " + r / 1e3 + " seconds.");
                var e = new Function(t + "()");
                srv[key].modal_open || srv[key].modal_dismissed || e(), idleState = !0;
            }, r);
        }), srv.jQuery("body").trigger("mousemove");
    }

    function userExit(e, r) {
        var t = !1, a = new Function(r + "()");
        srv.jQuery(document).bind("mouseout", function (e) {
            if (e = e || window.event, mouseFrom = e.relatedTarget || e.toElement, !mouseFrom || "HTML" == mouseFrom.nodeName) var r = e.pageY < 100;
            modal_open || modal_dismissed || !t || r && (t = !1, a());
        }), setTimeout(function () {
            t = !0;
        }, e);
    }

    function proActiveOpen(e, r) {
        var t = new Function(r + "()");
        setTimeout(function () {
            modal_open || modal_dismissed ? srv.log("already opened or dismissed") : t();
        }, e);
    }

    function triggerForUrl(e, r) {
        var t = document.location.href, a = !!r;
        for (var s in e = JSON.parse(e) || {}) if ("function" != typeof e[s]) {
            var o = e[s];
            if ("function" != typeof o) {
                var n = new RegExp(o, "g");
                t.match(n) && (a = !r);
            }
        }
        return a;
    }

    function scrollToFormPos(e) {
        var r = e.position, t = e.key, a = srv.jQuery('[data-key="' + t + '"]'),
            s = "slide" !== srv[t].slider.type ? a.closest(".surveyWindowWrap") : a.closest(".surveySliderScroller");
        "IFRAME" === !a[0].nodeName && (s = srv.jQuery("html, body"), r = 0 == r ? a.offset().top : r), setTimeout(function () {
            s.animate({scrollTop: r}, 225);
        }, 50);
    }

    function enableNext(e) {
        if (void 0 !== srv[e].survey_properties && 0 == srv[e].survey_properties.advanced.next_button_behaviour || void 0 === srv[e].required_map) return !1;
        var r = srv[e].required_map[srv[e].current_page];
        all_filled = !0, srv.jQuery(r).each(function (e, r) {
            var t = srv.jQuery("#" + r);
            t && (void 0 === t.val() || "" == srv.jQuery.trim(t.val())) && srv.jQuery(t).parent().is(":visible") && (all_filled = !1);
        });
        var t = srv.jQuery('[data-key="' + e + '"]').find("#page" + srv[e].current_page),
            a = t.find("#surveySubmitBtn")[0] ? t.find("#surveySubmitBtn") : t.find(".btn-next");
        "disable" == srv[e].survey_properties.advanced.next_button_behaviour ? a.prop("disabled", !all_filled) : "hide" == srv[e].survey_properties.advanced.next_button_behaviour && a.toggle(all_filled);
    }

    function scrollToSurveyElement(e, r) {
        window != window.top ? srv.callParentWindow({
            fn: "srv.scrollToFormPos",
            data: {position: e, key: r}
        }) : scrollToFormPos({position: e, key: r});
    }

    function transitionEnd() {
        var e, r, t = document.createElement("div"), a = {
            transition: "transitionend",
            OTransition: "oTransitionEnd",
            MozTransition: "transitionend",
            WebkitTransition: "webkitTransitionEnd"
        };
        for (e in a) if (void 0 !== t.style[e]) {
            r = a[e];
            break;
        }
        return void 0 !== r ? r : "notransition";
    }

    function setLock(e) {
        srv[e].lock_submit = !0, srv.jQuery('[data-key="' + e + '"]').find(".btn-next, .btn-submit").prop("disabled", !0);
    }

    function clearLock(e) {
        srv[e].lock_submit = !1, srv.jQuery('[data-key="' + e + '"]').find(".btn-next, .btn-submit").prop("disabled", !1);
    }

    function makeStars(e, i) {
        var l, r = e.find('input[type="radio"]'),
            d = (i = i || {}).hidden || srv.jQuery("#" + i.type + "_" + i.block_id),
            c = document.createDocumentFragment(), u = !1;
        i.showCaptions && (u = srv.jQuery("<span>").addClass("caption")), r.each(function (e, r) {
            var t = srv.jQuery(r), a = t.val(), s = t.attr("title"), o = e + 1,
                n = srv.jQuery("<div>").attr("data-value", a).attr("data-title", s).addClass("ui-stars-star").addClass("star_" + o).on("mouseenter", function () {
                    srv.jQuery(this).removeClass("ui-stars-star-on").siblings(".ui-stars-star").removeClass("ui-stars-star-on"), srv.jQuery(this).addClass("ui-stars-star-hover").prevAll(".ui-stars-star").addClass("ui-stars-star-hover"), u && u.text(srv.jQuery(this).attr("data-title"));
                }).on("mouseleave", function () {
                    srv.jQuery(this).removeClass("ui-stars-star-hover").prevAll(".ui-stars-star").removeClass("ui-stars-star-hover"), l && l.addClass("ui-stars-star-on").prevAll(".ui-stars-star").addClass("ui-stars-star-on"), u && !l ? u.text("") : u && l && u.text(l.attr("data-title"));
                }).on("click", function () {
                    var e = srv.jQuery(this).attr("data-value");
                    if (d.val(e).trigger("change"), l = srv.jQuery(this), srv.jQuery(this).siblings(".ui-stars-star").removeClass("ui-stars-star-on ui-stars-star-hover"), srv.jQuery(this).addClass("ui-stars-star-on").prevAll(".ui-stars-star").addClass("ui-stars-star-on"), i.callback && "function" == typeof i.callback) {
                        var r = i.callback_data || "";
                        i.callback(r);
                    }
                }).appendTo(c);
            srv.jQuery("<a>").text(a).appendTo(n);
            t.remove();
        }), u && u.appendTo(c), e.append(c);
    }

    function niceSelect(e, r, t) {
        var a = srv.jQuery("#" + this[t].div_name);

        function s(e) {
            e.after(srv.jQuery("<div></div>").addClass("nice-select").addClass(e.attr("class") || "").addClass(e.attr("disabled") ? "disabled" : "").attr("tabindex", e.attr("disabled") ? null : "0").html('<span class="current"></span><ul class="list"></ul>'));
            var a = e.next(), r = e.find("option"), t = e.find("option:selected");
            a.find(".current").html(t.data("display") || t.text()), r.each(function (e) {
                var r = srv.jQuery(this), t = r.data("display");
                a.find("ul").append(srv.jQuery("<li></li>").attr("data-value", r.val()).attr("data-display", t || null).addClass("option" + (r.is(":selected") ? " selected" : "") + (r.is(":disabled") ? " disabled" : "")).html(r.text()));
            });
        }

        "string" != typeof r ? (e.each(function () {
            var e = srv.jQuery(this);
            e.hide(), e.next().hasClass("nice-select") || s(e);
        }), a.off(".nice_select"), a.on("click.nice_select", ".nice-select", function (e) {
            var r = srv.jQuery(this);
            if (srv.jQuery(".nice-select").not(r).removeClass("open"), r.toggleClass("open"), r.hasClass("open")) {
                r.find(".option"), r.find(".focus").removeClass("focus"), r.find(".selected").addClass("focus");
                var t = srv.jQuery(window).height(), a = r.offset().top + r.outerHeight();
                t - a < 250 ? r.find(".list").css({maxHeight: t - a - 10}) : r.find(".list").css({maxHeight: ""});
            } else r.focus();
        }), a.on("click.nice_select", function (e) {
            0 === srv.jQuery(e.target).closest(".nice-select").length && srv.jQuery(".nice-select").removeClass("open").find(".option");
        }), a.on("click.nice_select", ".nice-select .option:not(.disabled)", function (e) {
            var r = srv.jQuery(this), t = r.closest(".nice-select");
            t.find(".selected").removeClass("selected"), r.addClass("selected");
            var a = r.data("display") || r.text();
            t.find(".current").text(a), t.prev("select").val(r.data("value")).trigger("change");
        }), a.on("keydown.nice_select", ".nice-select", function (e) {
            var r = srv.jQuery(this), t = srv.jQuery(r.find(".focus") || r.find(".list .option.selected"));
            if (32 == e.keyCode || 13 == e.keyCode) return r.hasClass("open") ? t.trigger("click") : r.trigger("click"), !1;
            if (40 == e.keyCode) {
                if (r.hasClass("open")) {
                    var a = t.nextAll(".option:not(.disabled)").first();
                    0 < a.length && (r.find(".focus").removeClass("focus"), a.addClass("focus"));
                } else r.trigger("click");
                return !1;
            }
            if (38 == e.keyCode) {
                if (r.hasClass("open")) {
                    var s = t.prevAll(".option:not(.disabled)").first();
                    0 < s.length && (r.find(".focus").removeClass("focus"), s.addClass("focus"));
                } else r.trigger("click");
                return !1;
            }
            if (27 == e.keyCode) r.hasClass("open") && r.trigger("click"); else if (9 == e.keyCode && r.hasClass("open")) return !1;
        })) : "update" == r ? e.each(function () {
            var e = srv.jQuery(this), r = srv.jQuery(this).next(".nice-select"), t = r.hasClass("open");
            r.length && (r.remove(), s(e), t && e.next().trigger("click"));
        }) : "destroy" == r ? (e.each(function () {
            var e = srv.jQuery(this), r = srv.jQuery(this).next(".nice-select");
            r.length && (r.remove(), e.css("display", ""));
        }), 0 == srv.jQuery(".nice-select").length && a.off(".nice_select")) : console.log('Method "' + r + '" does not exist.');
    }

    function getMeta(e) {
        var r = e.key;
        if (e.passback) return srv[r].window_viewport = e.viewport, srv[r].document_title = e.title, srv[r].trigger_method = e.trigger_method, void(srv[r].ga_id = e.ga_id);
        if (srv[r].outer_modal) {
            srv[r].window_viewport = srv.jQuery(window).width() + "x" + srv.jQuery(window).height(), srv[r].document_title = document.title || "";
            try {
                srv[r].ga_id = ga.getAll()[0].get("trackingId");
            } catch (e) {
            }
        } else srv.callParentWindow({fn: "srv.returnMeta", data: {key: r}});
    }

    function returnMeta(e) {
        var r = e.key || "", t = srv.jQuery(window).width() + "x" + srv.jQuery(window).height(), a = "";
        try {
            a = ga.getAll()[0].get("trackingId");
        } catch (e) {
        }
        srv.callChildWindow({
            fn: "srv.getMeta",
            data: {
                viewport: t,
                title: document.title,
                trigger_method: srv[r].trigger_method || "",
                key: r,
                ga_id: a,
                passback: !0
            }
        }, r);
    }

    function supportsPointerEvents(e) {
        var r = document.createElement("a").style;
        return r.cssText = "pointer-events:auto", "auto" === r.pointerEvents || (e && srv.jQuery("html").addClass("no-csspointerevents"), !1);
    }

    function supportsSvg() {
        return document.implementation.hasFeature("http://www.w3.org/TR/SVG11/feature#Image", "1.1");
    }

    function encodeQuotes(e) {
        var r = {'"': "&quot;", "'": "&#039;"};
        return e.replace(/["']/g, function (e) {
            return r[e];
        });
    }

    function iOSTest(e) {
        var r = parseFloat(("" + (/CPU.*OS ([0-9_]{1,5})|(CPU like).*AppleWebKit.*Mobile/i.exec(navigator.userAgent) || [0, ""])[1]).replace("undefined", "3_2").replace("_", ".").replace("_", "")) || !1;
        return e ? "fixed_positioning" === e ? !(!r || r && /CriOS/i.test(navigator.userAgent)) && r < 10.3 : "scroll_on_input" === e ? 10.2 < r : void 0 : r;
    }

    function fetchVariable(obj) {
        if ("string" == typeof obj && (obj = decodeURIComponent(obj)), "object" != typeof obj) try {
            obj = JSON.parse(obj);
        } catch (e) {
        }
        var key = obj.key, type = obj.type, value = obj.value, hidden_id = obj.hidden_id;
        srv.log("Fetching " + type + " data from :" + value, "info");
        var passback = "";
        if ("css_selector" === type) {
            var $selector;
            try {
                $selector = srv.jQuery(value.replace(/"/g, "'"));
            } catch (e) {
            }
            $selector && (passback = "INPUT" === $selector.prop("nodeName") || "TEXTAREA" === $selector.prop("nodeName") ? $selector.val() : srv.jQuery.trim($selector.text()));
        } else if ("js" === type) {
            var js_var;
            try {
                js_var = eval(value);
            } catch (e) {
            }
            if (void 0 !== js_var) {
                if ("object" == typeof js_var) try {
                    js_var = JSON.stringify(js_var);
                } catch (e) {
                }
                "string" != typeof js_var && "number" != typeof js_var || (passback = js_var);
            }
        } else if ("cookie" === type) {
            var cookie_val = getCookie(value);
            cookie_val && "string" == typeof cookie_val && (passback = cookie_val);
        } else if ("url_parameter" === type) {
            var url_param = getParameterByName(value, LOCATION_REF);
            url_param && "string" == typeof url_param && (passback = url_param);
        }
        if (passback) if (srv.log("Data found from " + type + ": " + value, "info"), obj.embedded) if (srv.jQuery("#" + hidden_id).length) srv.jQuery("#" + hidden_id).val(passback); else var poll = setInterval(function () {
            srv.jQuery("#" + hidden_id).length && (clearInterval(poll), srv.jQuery("#" + hidden_id).val(passback));
        }, 50); else srv.callChildWindow({
            fn: "(function(obj) {srv.jQuery('#'+obj.hidden_id).val(obj.data)})",
            data: {hidden_id: hidden_id, data: passback}
        }, key); else srv.log("No data found from " + type + ": " + value, "info");
    }

    function preloadSlider(t, e, r) {
        if (srv.jQuery('[data-key="' + t + '"]').length && !srv[t].is_loading) return "open" === e && srv.openSlider(!0, t), !0;
        if (!srv[t].is_loading) {
            var a = srv.jQuery("#" + srv[t].div_name), s = srv.jQuery("<div>").attr({
                    id: "surveyWindowWrap",
                    "data-parent-for": t
                }).addClass("surveyWindowWrap").css({
                    position: "fixed",
                    zIndex: 2000000001,
                    "backface-visibility": "hidden"
                }).addClass("mopinion-slider slide-" + srv[t].slider.position),
                o = "https://" + srv[t].domain + "/survey/public/modal?&key=" + t + "&domain=" + srv[t].domain + "&version=" + srv[t].version + "&parent=" + document.location,
                n = srv.jQuery("<div>").attr("id", "surveySliderScroller").addClass("surveySliderScroller").css({
                    maxHeight: "90vh",
                    overflowY: "hidden",
                    "-ms-overflow-style": "-ms-autohiding-scrollbar",
                    "-webkit-overflow-scrolling": "touch"
                }).appendTo(s),
                i = (srv.jQuery("<iframe>").attr("id", "surveyWindow").attr("src", o).attr("frameBorder", !1).attr("allowTransparency", !0).attr("data-key", t).addClass("surveyWindow").css({
                    border: "none",
                    borderRadius: "2px",
                    width: "100%",
                    background: "rgba(255,255,255,0.01)"
                }).appendTo(n), {});
            if ("right" === srv[t].slider.position ? i = {
                top: "10%",
                top: "calc(10% + 5px)",
                bottom: "auto",
                left: "auto",
                right: 0,
                transform: "translateX(100%)"
            } : "bottom-right" === srv[t].slider.position ? i = {
                top: "auto",
                bottom: "-5px",
                left: "auto",
                right: "2.5%",
                transform: "translateY(100%)"
            } : "bottom-left" === srv[t].slider.position ? i = {
                top: "auto",
                bottom: "-5px",
                left: "2.5%",
                right: "auto",
                transform: "translateY(100%)"
            } : "left" === srv[t].slider.position && (i = {
                top: "10%",
                top: "calc(10% + 5px)",
                left: 0,
                right: "auto",
                transform: "translateX(-100%)"
            }), r || srv[t].show_button) {
                var l = srv.addButton(t);
                l.appendTo(s), setTimeout(function () {
                    l.addClass("allow-button");
                }, 300);
            }
            return s.css(i).appendTo(a), sliderWidth(s), srv.jQuery(window).on("resize orientationchange", function () {
                sliderWidth(s);
            }), "open" === e && srv.jQuery(document).on("mopinion_loaded", function e(r) {
                r.detail.key === t && r.detail.iframe && (srv.openSlider(!0, t), srv.jQuery(document).off("mopinion_loaded", e));
            }), !0;
        }
    }

    function openSlider(e, r) {
        if (!r) {
            if (1 !== ALL_KEYS.length) {
                try {
                    srv.log("Key needed when multiple forms are initialized", !0, "warn");
                } catch (e) {
                }
                return;
            }
            r = ALL_KEYS[0];
        }
        if (getCookie("MSopened") == r && !e || srv[r].modal_open) {
            var t = "Not opening, reason: ";
            srv[r].modal_open ? t += "Already open" : getCookie("MSopened") == r && (t += "Got a cookie, and not forced by user"), isPreview && srv.jQuery("#surveyPreviewMessageContent").html(t), srv.log(t);
        } else {
            e && srv.log("User forced opening of modal", !1, "info");
            var a = srv.jQuery('[data-key="' + r + '"]'), s = a.closest(".surveyWindowWrap");
            if (!a.length) return void srv.preloadSlider(r, "open");
            srv[r].modalFirst = !0, animateTranslate(s, {
                from: -1 < ["right", "bottom-left", "bottom-right"].indexOf(srv[r].slider.position) ? "100" : "-100",
                to: "0",
                type: -1 < ["left", "right"].indexOf(srv[r].slider.position) ? "x" : "y"
            }, function () {
                srv.jQuery("html,body").addClass("mopinion-slider-active"), srv[r].modal_open = !0, srv.triggerEvent({
                    event: "shown",
                    key: r
                }), setCookies({type: "open", key: r});
            });
        }
        return !0;
    }

    function closeSlider(e) {
        if (srv[e].modal_open) {
            var r = srv.jQuery('[data-key="' + e + '"]').closest(".surveyWindowWrap");
            srv.clearCaptureEvents(e), animateTranslate(r, {
                to: -1 < ["right", "bottom-left", "bottom-right"].indexOf(srv[e].slider.position) ? "100" : "-100",
                type: -1 < ["left", "right"].indexOf(srv[e].slider.position) ? "x" : "y"
            }, function () {
                srv[e].modalFirst = !1;
                var t = srv[e].modal_open = !1;
                srv.jQuery.each(srv.ALL_KEYS, function (e, r) {
                    srv[r].modal_open && (t = !0);
                }), t || srv.jQuery("html,body").removeClass("mopinion-slider-active"), srv.triggerEvent({
                    event: "hidden",
                    key: e
                });
            });
        }
    }

    function sliderWidth(e) {
        var r = srv.jQuery(window).width();
        $iframe = !(!__MS.isDOM(e) && !__MS.isDOM(e[0])) && e, $iframe && (1200 < r ? frameWidth = "425px" : 992 < r && r < 1201 ? frameWidth = "380px" : r < 993 && 768 < r ? frameWidth = "380px" : r < 769 && 544 < r ? frameWidth = "320px" : r < 545 && (frameWidth = "260px"), $iframe.css({width: frameWidth}));
    }

    function animateTranslate(t, e, r) {
        if (t && "object" == typeof e) {
            var a = "y" != e.type ? "translateX" : "translateY", s = e.unit ? e.unit : "%", o = e.from || 0;
            srv.jQuery({noop: o}).animate({noop: e.to}, {
                duration: e.duration || 225, step: function (e, r) {
                    t.css("transform", a + "(" + e + s + ")");
                }, complete: r || !1
            });
        }
    }

    function clearForm(e) {
        var r = srv.jQuery('[data-key="' + e + '"]'), t = r.closest(".surveyWindowWrap"),
            a = srv.jQuery('[data-button-for="' + e + '"]');
        return r.remove(), t.remove(), a.remove(), srv.jQuery("#SRVvars" + e).remove(), -1 < ALL_KEYS.indexOf(e) && ALL_KEYS.splice(ALL_KEYS.indexOf(e), 1), delete srv[e], srv.log("Form " + e + " cleared."), !0;
    }

    function getPreselectValue(e, r) {
        return !!r && srv.getParameterByName(e, LOCATION_REF);
    }

    function loadWebFonts(e, r) {
        if ("string" == typeof e && (e = [].concat(e)), e instanceof Array) {
            var t = srv.jQuery.map(e, function (e) {
                if (e && !fontIsLoaded(e)) return e;
            });
            if (t.length) {
                var a = srv.jQuery.map(t, function (e) {
                    return e.replace(/ /g, "+");
                }).join("|");
                srv.log("Loading fonts: " + t.join(", "), !1, "info");
                var s = "https://fonts.mopinion.com/css?family=" + a;
                srv.appendStyle(s, "CUSTOMSRVFONTS" + r);
            }
        }
    }

    function fontIsLoaded(e) {
        if (document.fonts && "function" == typeof document.fonts.check && window.chrome) return document.fonts.check("12px " + e);
        var r = document.createElement("span");
        r.innerHTML = "giItT1WQy@!-/#", r.style.position = "absolute", r.style.left = "-10000px", r.style.top = "-10000px", r.style.fontSize = "300px", r.style.fontFamily = "sans-serif", r.style.fontVariant = "normal", r.style.fontStyle = "normal", r.style.fontWeight = "normal", r.style.letterSpacing = "0", document.body.appendChild(r);
        var t = r.offsetWidth;
        r.style.fontFamily = e + ", sans-serif";
        var a = r.offsetWidth !== t;
        return r.parentNode.removeChild(r), a;
    }

    function addAnalytics(e, r) {
        var t, a, s, o, n, i = e.GA_TRACKING_ID || srv[r].ga_id || "";
        !e.TYPE && e.load_analytics && i ? (srv.appendScript("https://www.googletagmanager.com/gtag/js?id=" + i), window.dataLayer = window.dataLayer || [], window.gtag = function () {
            dataLayer.push(arguments);
        }, gtag("js", new Date), gtag("config", i)) : "analytics.js" === e.TYPE && e.load_analytics && i && (t = window, a = document, s = "ga", t.GoogleAnalyticsObject = s, t.ga = t.ga || function () {
            (t.ga.q = t.ga.q || []).push(arguments);
        }, t.ga.l = 1 * new Date, o = a.createElement("script"), n = a.getElementsByTagName("script")[0], o.async = 1, o.src = "https://www.google-analytics.com/analytics.js", n.parentNode.insertBefore(o, n), ga("create", i, "auto"), ga("send", "pageview"));
    }

    var Sobject = {};
    Sobject.resizeListener = resizeListener, Sobject.callParentWindow = callParentWindow, Sobject.callChildWindow = callChildWindow, Sobject.resizeForm = resizeForm, Sobject.send = send, Sobject.appendScript = appendScript, Sobject.appendStyle = appendStyle, Sobject.loadHelpers = loadHelpers, Sobject.loadSurvey = loadSurvey, Sobject.loadJSON = loadJSON, Sobject.buildForm = buildForm, Sobject.generateHTML = generateHTML, Sobject.log = log, Sobject.prevPage = prevPage, Sobject.nextPage = nextPage, Sobject.setValue = setValue, Sobject.submitSurvey = submitSurvey, Sobject.openModal = openModal, Sobject.closeModal = closeModal, Sobject.hideLoader = hideLoader, Sobject.request = request, Sobject.updateProgress = updateProgress, Sobject.getLanguage = getLanguage, Sobject.removeButton = removeButton, Sobject.addButton = addButton, Sobject.showButton = showButton, Sobject.getParameterByName = getParameterByName, Sobject.makeStars = makeStars, Sobject.niceSelect = niceSelect, Sobject.setCookie = setCookie, Sobject.getCookie = getCookie, Sobject.setCookies = setCookies, Sobject.triggerEvent = triggerEvent, Sobject.on = EventHandler, Sobject.initEvent = EventInitializer, Sobject.loadjQuery = loadjQuery, Sobject.loadjQueryHandler = loadjQueryHandler, Sobject.proActiveOpen = proActiveOpen, Sobject.userExit = userExit, Sobject.triggerForUrl = triggerForUrl, Sobject.setPrefill = setPrefill, Sobject.hideBlock = hideBlock, Sobject.unhideBlock = unhideBlock, Sobject.scrollToFormPos = scrollToFormPos, Sobject.initCapture = initCapture, Sobject.catchCapture = catchCapture, Sobject.showModalPostCapture = showModalPostCapture, Sobject.clearCaptureEvents = clearCaptureEvents, Sobject.clearCaptureValues = clearCaptureValues, Sobject.getMeta = getMeta, Sobject.returnMeta = returnMeta, Sobject.fetchVariable = fetchVariable, Sobject.open = opener, Sobject.clearForm = clearForm, Sobject.loadWebFonts = loadWebFonts, Sobject.preloadSlider = preloadSlider, Sobject.openSlider = openSlider, Sobject.closeSlider = closeSlider, Sobject.sliderWidth = sliderWidth, Sobject.show_log = show_log, Sobject.ALL_KEYS = ALL_KEYS, Sobject.PUBLIC_LANDING = PUBLIC_LANDING, Sobject.LOCATION_REF = LOCATION_REF, Sobject.eventsHandlers = eventsHandlers;
    try {
        srv.log("loading Surveys " + version, !1, "info");
    } catch (e) {
    }
    return Sobject;
}(), mopinion = srv, __MS = function () {
    MSobject = {};
    var h = new RegExp(/^len[0-9]+/), _ = new RegExp(/^max_len[0-9]+/),
        g = (new RegExp(/^(((0)[1-9]{2}[0-9][-]?[1-9][0-9]{5})|((\+31|0|0031)[1-9][0-9][-]?[1-9][0-9]{6}))|((\\+31|0|0031)6){1}[-]?[1-9]{1}[0-9]{7}$/), new RegExp(/^[1-9][0-9]{3}[\s]?[A-Za-z]{2}$/i), new RegExp(/^(\+){0,1}[0-9-.\(\)]{6,25}$/));

    function b(e) {
        var r = e.indexOf("@"), t = e.lastIndexOf(".");
        return !(r < 1 || t < r + 2 || t + 2 >= e.length);
    }

    function j(e, r) {
        for (var t = 0; t < e.length; t++) if ("string" == typeof r) {
            if (e[t] == r) return !0;
        } else if ("object" == typeof r && e[t].match(r)) return !0;
        return !1;
    }

    return Array.prototype.indexOf || (Array.prototype.indexOf = function (e) {
        for (var r = 0; r < this.length; r++) if (this[r] === e) return r;
        return -1;
    }), "function" != typeof window.CustomEvent && (window.customEvent = function (e, r) {
        r = r || {bubbles: !1, cancelable: !1, detail: void 0};
        var t = document.createEvent("CustomEvent");
        return t.initCustomEvent(e, r.bubbles, r.cancelable, r.detail), t;
    }), MSobject.inArray = j, MSobject.log = function (e, r, t) {
        if (t && "log" != t) {
            if ("warn" == t) {
                if (srv.show_log || !0 === r) try {
                    console.warn(e);
                } catch (e) {
                }
            } else if ("info" == t) {
                if (srv.show_log || !0 === r) try {
                    console.info(e);
                } catch (e) {
                }
            } else if ("error" == t) throw new Error(e);
        } else if (srv.show_log || !0 === r) try {
            console.log(e);
        } catch (e) {
        }
    }, MSobject.validateAll = function (r, t) {
        var a = !0;
        if (r) {
            var s = [];
            for (var o in r) "string" == typeof r[o] && jQuery("#" + r[o]).hasClass("req") && s.push(jQuery("#" + r[o])[0]);
        } else s = jQuery(".req").toArray();
        for (var n = 0; n < s.length; n++) {
            var i = s[n];
            if (-1 != navigator.userAgent.indexOf("MSIE 8.0") ? (setTimeout(function () {
                "object" == typeof i && i.focus();
            }, 0), setTimeout(function () {
                "object" == typeof i && i.blur();
            }, 0)) : (i.focus(), i.blur()), "" == i.value || null == i.value || -1 != i.className.indexOf("numeric") && 0 == i.value || -1 != i.className.indexOf("phone") && !i.value.match(g)) {
                var l = __MS.validateField(i);
                t && t(i, l), a = !1;
            }
            "checkbox" == i.type && (__MS.unsetError(i), i.checked || (__MS.setError(i), a = !1));
        }
        if (0 != this.errors.length) if (r) for (e in this.errors) "function" != typeof this.errors[e] && -1 < r.indexOf(this.errors[e]) && (a = !1); else a = !1;
        return a;
    }, MSobject.validateEmail = b, MSobject.validateField = function (e, r, t) {
        if ($field = jQuery(e), $field[0]) {
            var a, s = !0, o = !1, n = !1, i = !0, l = !0, d = !0, c = !1, u = e.className.split(" "),
                v = $field.hasClass("email"), p = $field.hasClass("numeric"), f = $field.hasClass("phone"),
                y = $field.hasClass("req");
            if (j(u, h) || j(u, _)) for (var m = 0; m < u.length; m++) -1 < u[m].search(h) && (n = u[m].replace("len", "")), -1 < u[m].search(_) && (o = u[m].replace("max_len", ""));
            return jQuery.trim(e.value).length < n ? (s = !1, a = "too_short") : !1 !== o && jQuery.trim(e.value).length > o && e.value !== r && (s = !1, a = "too_long"), f && $field.val() != r && "" !== jQuery.trim($field.val()) && (e.value.match(g) || (d = !1)), p && $field.val() !== r && "" !== jQuery.trim($field.val()) && ((parseInt($field.val()) || parseFloat($field.val())) && isFinite($field.val()) && 0 != parseFloat($field.val()) || (l = !1)), v && "" !== jQuery.trim($field.val()) && (i = b($field.val())), !1 === l || !1 === i || !1 === d || !1 === s || ("" === jQuery.trim($field.val()) || null === $field.val() || $field.val() === r) && y ? (null !== r && "" === jQuery.trim($field.val()) && void 0 !== r && $field.val(r), i ? d ? l ? a || (a = "required") : a = "invalid_number" : a = "invalid_phone" : a = "invalid_email", __MS.setError($field), c = !1) : (__MS.unsetError($field), c = !0), return_obj = {
                state: c,
                code: a
            };
        }
    }, MSobject.setError = function (e) {
        var r = e.attr("id");
        jQuery("#" + r + "_error_style"), j(this.errors, r) || this.errors.push(r), e.addClass("error"), e.parent().addClass("error");
    }, MSobject.unsetError = function (e) {
        e instanceof jQuery == 0 && (e = jQuery(e));
        var t = e.attr("id"), a = this.errors;
        e.removeClass("error"), e.parent().removeClass("error"), jQuery("#" + t + "_error").remove(), jQuery(a).each(function (e, r) {
            r == t && delete a[e];
        }), this.errors = function (e) {
            var r = [];
            for (var t in e) "function" != typeof e[t] && void 0 !== e[t] && r.push(e[t]);
            return r;
        }(a);
    }, MSobject.getCookie = function (e) {
        var r, t, a, s = document.cookie.split(";");
        for (r = 0; r < s.length; r++) if (t = s[r].substr(0, s[r].indexOf("=")), a = s[r].substr(s[r].indexOf("=") + 1), (t = t.replace(/^\s+|\s+$/g, "")) == e) return unescape(a);
    }, MSobject.setCookie = function (e, r, t, a, s) {
        var o = new Date;
        -1 == t ? (o.setMonth(o.getMonth() - 1), cexp = "expires=" + o + ";") : 1 == t ? (o.setMonth(o.getMonth() + 1), cexp = "expires=" + o + ";") : cexp = "", cpath = a ? "path=" + a + ";" : "path=" + document.location.pathname + ";", cdomain = s ? "domain=" + s + ";" : "";
        var n = e + "=" + r + ";" + cexp + cpath + cdomain;
        document.cookie = n;
    }, MSobject.isDOM = function (e) {
        return "HTMLElement" in window ? e && e instanceof HTMLElement : !(!e || "object" != typeof e || 1 !== e.nodeType || !e.nodeName);
    }, MSobject.hammerTime = function () {
        var e = window.MutationObserver || window.WebKitMutationObserver,
            r = "ontouchstart" in window || window.DocumentTouch && document instanceof DocumentTouch;
        if (void 0 === document.documentElement.style["touch-action"] && !document.documentElement.style["-ms-touch-action"] && r && e) {
            window.Hammer = window.Hammer || {};
            var t = /touch-action[:][\s]*(none)[^;'"]*/, a = /touch-action[:][\s]*(manipulation)[^;'"]*/,
                s = /touch-action/, o = !!navigator.userAgent.match(/(iPad|iPhone|iPod)/g), n = function () {
                    try {
                        var e = document.createElement("canvas");
                        return !(!window.WebGLRenderingContext || !e.getContext("webgl") && !e.getContext("experimental-webgl"));
                    } catch (e) {
                        return !1;
                    }
                }() && o;
            window.Hammer.time = {
                getTouchAction: function (e) {
                    return this.checkStyleString(e.getAttribute("style"));
                }, checkStyleString: function (e) {
                    if (s.test(e)) return t.test(e) ? "none" : !a.test(e) || "manipulation";
                }, shouldHammer: function (e) {
                    var r = this.hasParent(e.target);
                    return !(!r || n && !(Date.now() - e.target.lastStart < 125)) && r;
                }, touchHandler: function (e) {
                    var r = e.target.getBoundingClientRect(), t = r.top !== this.pos.top || r.left !== this.pos.left,
                        a = this.shouldHammer(e);
                    ("none" === a || !1 === t && "manipulation" === a) && ("touchend" === e.type && (e.target.focus(), setTimeout(function () {
                        e.target.click();
                    }, 0)), e.preventDefault()), this.scrolled = !1, delete e.target.lastStart;
                }, touchStart: function (e) {
                    this.pos = e.target.getBoundingClientRect(), n && this.hasParent(e.target) && (e.target.lastStart = Date.now());
                }, styleWatcher: function (e) {
                    e.forEach(this.styleUpdater, this);
                }, styleUpdater: function (e) {
                    if (e.target.updateNext) e.target.updateNext = !1; else {
                        var r = this.getTouchAction(e.target);
                        r ? "none" !== r && (e.target.hadTouchNone = !1) : !r && (e.oldValue && this.checkStyleString(e.oldValue) || e.target.hadTouchNone) && (e.target.hadTouchNone = !0, e.target.updateNext = !1, e.target.setAttribute("style", e.target.getAttribute("style") + " touch-action: none;"));
                    }
                }, hasParent: function (e) {
                    for (var r, t = e; t && t.parentNode; t = t.parentNode) if (r = this.getTouchAction(t)) return r;
                    return !1;
                }, installStartEvents: function () {
                    document.addEventListener("touchstart", this.touchStart.bind(this)), document.addEventListener("mousedown", this.touchStart.bind(this));
                }, installEndEvents: function () {
                    document.addEventListener("touchend", this.touchHandler.bind(this), !0), document.addEventListener("mouseup", this.touchHandler.bind(this), !0);
                }, installObserver: function () {
                    this.observer = new e(this.styleWatcher.bind(this)).observe(document, {
                        subtree: !0,
                        attributes: !0,
                        attributeOldValue: !0,
                        attributeFilter: ["style"]
                    });
                }, install: function () {
                    this.installEndEvents(), this.installStartEvents(), this.installObserver();
                }
            }, window.Hammer.time.install();
        }
    }, MSobject.extend = function (e) {
        e = e || {};
        for (var r = 1; r < arguments.length; r++) if (arguments[r]) for (var t in arguments[r]) arguments[r].hasOwnProperty(t) && (e[t] = arguments[r][t]);
        return e;
    }, MSobject.errors = [], MSobject;
}();
