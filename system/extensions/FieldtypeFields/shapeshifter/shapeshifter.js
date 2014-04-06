(function () {
    (function (e, t, n) {
        var r, i, s;
        s = "shapeshift";
        i = {selector: "*", enableDrag: true, enableCrossDrop: true, enableResize: true, enableTrash: false, align: "center", colWidth: null, columns: null, minColumns: 1, autoHeight: true, maxHeight: null, minHeight: 100, gutterX: 10, gutterY: 10, paddingX: 10, paddingY: 10, animated: true, animateOnInit: false, animationSpeed: 225, animationThreshold: 100, dragClone: false, deleteClone: true, dragRate: 100, dragWhitelist: "*", crossDropWhitelist: "*", cutoffStart: null, cutoffEnd: null, handle: false, cloneClass: "ss-cloned-child", activeClass: "ss-active-child", draggedClass: "ss-dragged-child", placeholderClass: "ss-placeholder-child", originalContainerClass: "ss-original-container", currentContainerClass: "ss-current-container", previousContainerClass: "ss-previous-container"};
        r = function () {
            function n(t, n) {
                this.element = t;
                this.options = e.extend({}, i, n);
                this.globals = {};
                this.$container = e(t);
                if (this.errorCheck()) {
                    this.init()
                }
            }

            n.prototype.errorCheck = function () {
                var e, t, n, r;
                r = this.options;
                n = false;
                t = "Shapeshift ERROR:";
                if (r.colWidth === null) {
                    e = this.$container.children(r.selector);
                    if (e.length === 0) {
                        n = true;
                        console.error("" + t + " option colWidth must be specified if Shapeshift is initialized with no active children.")
                    }
                }
                return!n
            };
            n.prototype.init = function () {
                this.createEvents();
                this.setGlobals();
                this.setIdentifier();
                this.setActiveChildren();
                this.enableFeatures();
                this.gridInit();
                this.render();
                return this.afterInit()
            };
            n.prototype.createEvents = function () {
                var e, t, n = this;
                t = this.options;
                e = this.$container;
                e.off("ss-arrange").on("ss-arrange", function (e, t) {
                    if (t == null) {
                        t = false
                    }
                    return n.render(false, t)
                });
                e.off("ss-rearrange").on("ss-rearrange", function () {
                    return n.render(true)
                });
                e.off("ss-setTargetPosition").on("ss-setTargetPosition", function () {
                    return n.setTargetPosition()
                });
                return e.off("ss-destroy").on("ss-destroy", function () {
                    return n.destroy()
                })
            };
            n.prototype.setGlobals = function () {
                return this.globals.animated = this.options.animateOnInit
            };
            n.prototype.afterInit = function () {
                return this.globals.animated = this.options.animated
            };
            n.prototype.setIdentifier = function () {
                this.identifier = "shapeshifted_container_" + Math.random().toString(36).substring(7);
                return this.$container.addClass(this.identifier)
            };
            n.prototype.enableFeatures = function () {
                if (this.options.enableResize) {
                    this.enableResize()
                }
                if (this.options.enableDrag || this.options.enableCrossDrop) {
                    return this.enableDragNDrop()
                }
            };
            n.prototype.setActiveChildren = function () {
                var t, n, r, i, s, o, u, a, f, l, c, h;
                u = this.options;
                t = this.$container.children(u.selector);
                n = u.activeClass;
                a = t.length;
                for (s = f = 0; 0 <= a ? f < a : f > a; s = 0 <= a ? ++f : --f) {
                    e(t[s]).addClass(n)
                }
                this.setParsedChildren();
                i = u.columns;
                h = [];
                for (s = l = 0, c = this.parsedChildren.length; 0 <= c ? l < c : l > c; s = 0 <= c ? ++l : --l) {
                    r = this.parsedChildren[s].colspan;
                    o = u.minColumns;
                    if (r > i && r > o) {
                        u.minColumns = r;
                        h.push(console.error("Shapeshift ERROR: There are child elements that have a larger colspan than the minimum columns set through options.\noptions.minColumns has been set to " + r))
                    } else {
                        h.push(void 0)
                    }
                }
                return h
            };
            n.prototype.setParsedChildren = function () {
                var t, n, r, i, s, o, u;
                n = this.$container.find("." + this.options.activeClass).filter(":visible");
                o = n.length;
                s = [];
                for (i = u = 0; 0 <= o ? u < o : u > o; i = 0 <= o ? ++u : --u) {
                    t = e(n[i]);
                    r = {i: i, el: t, colspan: parseInt(t.attr("data-ss-colspan")) || 1, height: t.outerHeight()};
                    s.push(r)
                }
                return this.parsedChildren = s
            };
            n.prototype.gridInit = function () {
                var e, t, n, r, i;
                r = this.options.gutterX;
                if (!(this.options.colWidth >= 1)) {
                    n = this.parsedChildren[0];
                    t = n.el.outerWidth();
                    e = n.colspan;
                    i = (t - (e - 1) * r) / e;
                    return this.globals.col_width = i + r
                } else {
                    return this.globals.col_width = this.options.colWidth + r
                }
            };
            n.prototype.render = function (e, t) {
                if (e == null) {
                    e = false
                }
                this.setGridColumns();
                return this.arrange(e, t)
            };
            n.prototype.setGridColumns = function () {
                var e, t, n, r, i, s, o, u, a, f;
                r = this.globals;
                a = this.options;
                t = r.col_width;
                s = a.gutterX;
                f = a.paddingX;
                o = this.$container.innerWidth() - f * 2;
                u = a.minColumns;
                n = a.columns || Math.floor((o + s) / t);
                if (u && u > n) {
                    n = u
                }
                r.columns = n;
                e = this.parsedChildren.length;
                if (n > e) {
                    n = e
                }
                r.child_offset = f;
                switch (a.align) {
                    case"center":
                        i = n * t - s;
                        return r.child_offset += (o - i) / 2;
                    case"right":
                        i = n * t - s;
                        return r.child_offset += o - i
                }
            };
            n.prototype.arrange = function (e, t) {
                var n, r, i, s, o, u, a, f, l, c, h, p, d, v, m, g, y, b;
                if (e) {
                    this.setParsedChildren()
                }
                l = this.globals;
                v = this.options;
                r = this.$container;
                u = this.getPositions();
                m = this.parsedChildren;
                y = m.length;
                i = l.animated && y <= v.animationThreshold;
                s = v.animationSpeed;
                f = v.draggedClass;
                for (c = b = 0; 0 <= y ? b < y : b > y; c = 0 <= y ? ++b : --b) {
                    n = m[c].el;
                    o = u[c];
                    h = n.hasClass(f);
                    if (h) {
                        g = v.placeholderClass;
                        n = n.siblings("." + g)
                    }
                    if (i && !h) {
                        n.stop(true, false).animate(o, s, function () {
                        })
                    } else {
                        n.css(o)
                    }
                }
                if (t) {
                    if (i) {
                        setTimeout(function () {
                            return r.trigger("ss-drop-complete")
                        }, s)
                    } else {
                        r.trigger("ss-drop-complete")
                    }
                }
                r.trigger("ss-arranged");
                if (v.autoHeight) {
                    a = l.container_height;
                    p = v.maxHeight;
                    d = v.minHeight;
                    if (d && a < d) {
                        a = d
                    } else if (p && a > p) {
                        a = p
                    }
                    return r.height(a)
                }
            };
            n.prototype.getPositions = function (e) {
                var t, n, r, i, s, o, u, a, f, l, c, h, p, d, v, m, g, y, b = this;
                if (e == null) {
                    e = true
                }
                s = this.globals;
                f = this.options;
                u = f.gutterY;
                l = f.paddingY;
                i = f.draggedClass;
                c = this.parsedChildren;
                m = c.length;
                t = [];
                for (a = g = 0, y = s.columns; 0 <= y ? g < y : g > y; a = 0 <= y ? ++g : --g) {
                    t.push(l)
                }
                d = function (e) {
                    var n, r, i, o, a, f, l;
                    n = e.col;
                    r = e.colspan;
                    o = e.col * s.col_width + s.child_offset;
                    a = t[n];
                    h[e.i] = {left: o, top: a};
                    t[n] += e.height + u;
                    if (r >= 1) {
                        l = [];
                        for (i = f = 1; 1 <= r ? f < r : f > r; i = 1 <= r ? ++f : --f) {
                            l.push(t[n + i] = t[n])
                        }
                        return l
                    }
                };
                n = function (e) {
                    var n, r, i, s, o, u, a, f, l, c, h, p;
                    l = t.length - e.colspan + 1;
                    f = t.slice(0).splice(0, l);
                    n = void 0;
                    for (a = h = 0; 0 <= l ? h < l : h > l; a = 0 <= l ? ++h : --h) {
                        r = b.lowestCol(f, a);
                        i = e.colspan;
                        s = t[r];
                        o = true;
                        for (c = p = 1; 1 <= i ? p < i : p > i; c = 1 <= i ? ++p : --p) {
                            u = t[r + c];
                            if (s < u) {
                                o = false;
                                break
                            }
                        }
                        if (o) {
                            n = r;
                            break
                        }
                    }
                    return n
                };
                v = [];
                p = function () {
                    var e, t, r, i, s, o, u, a, f, l;
                    s = [];
                    for (i = o = 0, a = v.length; 0 <= a ? o < a : o > a; i = 0 <= a ? ++o : --o) {
                        r = v[i];
                        r.col = n(r);
                        if (r.col >= 0) {
                            d(r);
                            s.push(i)
                        }
                    }
                    l = [];
                    for (t = u = f = s.length - 1; u >= 0; t = u += -1) {
                        e = s[t];
                        l.push(v.splice(e, 1))
                    }
                    return l
                };
                h = [];
                (r = function () {
                    var r, s, o;
                    o = [];
                    for (a = s = 0; 0 <= m ? s < m : s > m; a = 0 <= m ? ++s : --s) {
                        r = c[a];
                        if (!(!e && r.el.hasClass(i))) {
                            if (r.colspan > 1) {
                                r.col = n(r)
                            } else {
                                r.col = b.lowestCol(t)
                            }
                            if (r.col === void 0) {
                                v.push(r)
                            } else {
                                d(r)
                            }
                            o.push(p())
                        } else {
                            o.push(void 0)
                        }
                    }
                    return o
                })();
                if (f.autoHeight) {
                    o = t[this.highestCol(t)] - u;
                    s.container_height = o + l
                }
                return h
            };
            n.prototype.enableDragNDrop = function () {
                var n, r, i, s, o, u, a, f, l, c, h, p, d, v, m, g, y, b, w = this;
                d = this.options;
                r = this.$container;
                o = d.activeClass;
                h = d.draggedClass;
                m = d.placeholderClass;
                v = d.originalContainerClass;
                a = d.currentContainerClass;
                g = d.previousContainerClass;
                f = d.deleteClone;
                c = d.dragRate;
                l = d.dragClone;
                u = d.cloneClass;
                s = i = n = b = y = null;
                p = false;
                if (d.enableDrag) {
                    r.children("." + o).filter(d.dragWhitelist).draggable({addClasses: false, containment: "document", handle: d.handle, zIndex: 9999, start: function (t, r) {
                        var o;
                        s = e(t.target);
                        if (l) {
                            n = s.clone(true).insertBefore(s).addClass(u)
                        }
                        s.addClass(h);
                        o = s.prop("tagName");
                        i = e("<" + o + " class='" + m + "' style='height: " + s.height() + "px; width: " + s.width() + "px'></" + o + ">");
                        s.parent().addClass(v).addClass(a);
                        b = s.outerHeight() / 2;
                        return y = s.outerWidth() / 2
                    }, drag: function (n, r) {
                        if (!p && !(l && f && e("." + a)[0] === e("." + v)[0])) {
                            i.remove().appendTo("." + a);
                            e("." + a).trigger("ss-setTargetPosition");
                            p = true;
                            t.setTimeout(function () {
                                return p = false
                            }, c)
                        }
                        r.position.left = n.pageX - s.parent().offset().left - y;
                        return r.position.top = n.pageY - s.parent().offset().top - b
                    }, stop: function () {
                        var t, r, o;
                        r = e("." + v);
                        t = e("." + a);
                        o = e("." + g);
                        s.removeClass(h);
                        e("." + m).remove();
                        if (l) {
                            if (f && e("." + a)[0] === e("." + v)[0]) {
                                n.remove();
                                e("." + a).trigger("ss-rearrange")
                            } else {
                                n.removeClass(u)
                            }
                        }
                        if (r[0] === t[0]) {
                            t.trigger("ss-rearranged", s)
                        } else {
                            r.trigger("ss-removed", s);
                            t.trigger("ss-added", s)
                        }
                        r.trigger("ss-arrange").removeClass(v);
                        t.trigger("ss-arrange", true).removeClass(a);
                        o.trigger("ss-arrange").removeClass(g);
                        return s = i = null
                    }})
                }
                if (d.enableCrossDrop) {
                    return r.droppable({accept: d.crossDropWhitelist, tolerance: "intersect", over: function (t) {
                        e("." + g).removeClass(g);
                        e("." + a).removeClass(a).addClass(g);
                        return e(t.target).addClass(a)
                    }, drop: function (t, n) {
                        var r, i, o;
                        if (w.options.enableTrash) {
                            i = e("." + v);
                            r = e("." + a);
                            o = e("." + g);
                            s = e(n.helper);
                            r.trigger("ss-trashed", s);
                            s.remove();
                            i.trigger("ss-rearrange").removeClass(v);
                            r.trigger("ss-rearrange").removeClass(a);
                            return o.trigger("ss-arrange").removeClass(g)
                        }
                    }})
                }
            };
            n.prototype.setTargetPosition = function () {
                var t, n, r, i, s, o, u, a, f, l, c, h, p, d, v, m, g, y, b, w, E, S;
                l = this.options;
                if (!l.enableTrash) {
                    f = l.draggedClass;
                    t = e("." + f);
                    n = t.parent();
                    c = this.parsedChildren;
                    s = this.getPositions(false);
                    b = s.length;
                    v = t.offset().left - n.offset().left + this.globals.col_width / 2;
                    m = t.offset().top - n.offset().top + t.height() / 2;
                    g = 9999999;
                    y = 0;
                    if (b > 1) {
                        u = l.cutoffStart + 1 || 0;
                        o = l.cutoffEnd || b;
                        for (p = S = u; u <= o ? S < o : S > o; p = u <= o ? ++S : --S) {
                            i = s[p];
                            if (i) {
                                E = v - i.left;
                                w = m - i.top;
                                if (E > 0 && w > 0) {
                                    a = Math.sqrt(w * w + E * E);
                                    if (a < g) {
                                        g = a;
                                        y = p;
                                        if (p === b - 1) {
                                            if (E > c[p].height / 2) {
                                                y++
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        if (y === c.length) {
                            r = c[y - 1].el;
                            t.insertAfter(r)
                        } else {
                            r = c[y].el;
                            t.insertBefore(r)
                        }
                    } else {
                        if (b === 1) {
                            i = s[0];
                            if (i.left < v) {
                                this.$container.append(t)
                            } else {
                                this.$container.prepend(t)
                            }
                        } else {
                            this.$container.append(t)
                        }
                    }
                    this.arrange(true);
                    if (n[0] !== t.parent()[0]) {
                        d = l.previousContainerClass;
                        return e("." + d).trigger("ss-rearrange")
                    }
                } else {
                    h = this.options.placeholderClass;
                    return e("." + h).remove()
                }
            };
            n.prototype.enableResize = function () {
                var n, r, i, s = this;
                n = this.options.animationSpeed;
                i = false;
                r = "resize." + this.identifier;
                return e(t).on(r, function () {
                    if (!i) {
                        i = true;
                        setTimeout(function () {
                            return s.render()
                        }, n / 3);
                        setTimeout(function () {
                            return s.render()
                        }, n / 3);
                        return setTimeout(function () {
                            i = false;
                            return s.render()
                        }, n / 3)
                    }
                })
            };
            n.prototype.lowestCol = function (e, t) {
                var n, r, i, s;
                if (t == null) {
                    t = 0
                }
                i = e.length;
                n = [];
                for (r = s = 0; 0 <= i ? s < i : s > i; r = 0 <= i ? ++s : --s) {
                    n.push([e[r], r])
                }
                n.sort(function (e, t) {
                    var n;
                    n = e[0] - t[0];
                    if (n === 0) {
                        n = e[1] - t[1]
                    }
                    return n
                });
                return n[t][1]
            };
            n.prototype.highestCol = function (n) {
                return e.inArray(Math.max.apply(t, n), n)
            };
            n.prototype.destroy = function () {
                var e, t, n;
                t = this.$container;
                t.off("ss-arrange");
                t.off("ss-rearrange");
                t.off("ss-setTargetPosition");
                t.off("ss-destroy");
                n = this.options.activeClass;
                e = t.find("." + n);
                if (this.options.enableDrag) {
                    e.draggable("destroy")
                }
                if (this.options.enableCrossDrop) {
                    t.droppable("destroy")
                }
                e.removeClass(n);
                return t.removeClass(this.identifier)
            };
            return n
        }();
        return e.fn[s] = function (n) {
            return this.each(function () {
                var i, o, u;
                o = (u = e(this).attr("class").match(/shapeshifted_container_\w+/)) != null ? u[0] : void 0;
                if (o) {
                    i = "resize." + o;
                    e(t).off(i);
                    e(this).removeClass(o)
                }
                return e.data(this, "plugin_" + s, new r(this, n))
            })
        }
    })(jQuery, window, document)
}).call(this)