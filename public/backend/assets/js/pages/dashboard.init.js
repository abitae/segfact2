!(function (e) {
  "use strict";
  function t() {}
  (t.prototype.createAreaChart = function (e, t, a, i, r, o, n, c) {
    Morris.Area({ element: e, pointSize: 0, lineWidth: 0, data: i, xkey: r, ykeys: o, labels: n, resize: !0, gridLineColor: "#eeee", hideHover: "auto", lineColors: c, fillOpacity: 0.7, behaveLikeLine: !0 });
  }),
    (t.prototype.createDonutChart = function (e, t, a) {
      Morris.Donut({ element: e, data: t, resize: !0, colors: a });
    }),
    e(".peity-pie").each(function () {
      e(this).peity("pie", e(this).data());
    }),
    e(".peity-donut").each(function () {
      e(this).peity("donut", e(this).data());
    }),
    (t.prototype.init = function () {
      this.createAreaChart(
        "morris-area-example",
        0,
        0,
        [
          { y: "2011", a: 0, b: 0, c: 0 },
          { y: "2012", a: 150, b: 45, c: 15 },
          { y: "2013", a: 60, b: 150, c: 195 },
          { y: "2014", a: 180, b: 36, c: 21 },
          { y: "2015", a: 90, b: 60, c: 360 },
          { y: "2016", a: 75, b: 240, c: 120 },
          { y: "2017", a: 30, b: 30, c: 30 },
        ],
        "y",
        ["a", "b", "c"],
        ["Series A", "Series B", "Series C"],
        ["#ccc", "#f5b225", "#1b82ec"]
      );
      this.createDonutChart(
        "morris-donut-example",
        [
          { label: "Download Sales", value: 12 },
          { label: "In-Store Sales", value: 30 },
          { label: "Mail-Order Sales", value: 20 },
        ],
        ["#f0f1f4", "#1b82ec", "#f5b225"]
      );
    }),
    (e.Dashboard = new t()),
    (e.Dashboard.Constructor = t);
})(window.jQuery),
  (function () {
    "use strict";
    // window.jQuery.Dashboard.init();
  })();
