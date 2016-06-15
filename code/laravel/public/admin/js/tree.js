    //Sort a query with id and beforeid so that the rows have the correct order of the tree
    /*sorts query from this:
      [
        {"id": 456, "before": 123, "name": "Dogs"},
        {"id": 214, "before": 456, "name": "drilling machine A"},
        {"id": 123, "before": null, "name": "Tools"},
        {"id": 810, "before": 456, "name": "drilling machine B"},
        {"id": 919, "before": 456, "name": "drilling machine C"}
      ] 

      to this:
      [
        {"id": 123, "before": null, "name": "Tools"},
        {"id": 456, "before": 123, "name": "drilling machine"},
        {"id": 214, "before": 456, "name": "drilling machine A"},
        {"id": 810, "before": 456, "name": "drilling machine B"},
        {"id": 919, "before": 456, "name": "drilling machine C"}
      ] */
    var _queryTreeSort = function(options) {
      var cfi, e, i, id, o, pid, rfi, ri, thisid, _i, _j, _len, _len1, _ref, _ref1;
      id = options.id || "id";
      pid = options.BeforeID || "BeforeID";
      ri = []; // Root items
      rfi = {}; // Rows from id
      cfi = {}; // Children from id
      o = [];
      _ref = options.q;
      // Setup Indexing
      for (i = _i = 0, _len = _ref.length; _i < _len; i = ++_i) {
        e = _ref[i];
        rfi[e[id]] = i;
        if (cfi[e[pid]] == null) {
          cfi[e[pid]] = [];
        }
        cfi[e[pid]].push(options.q[i][id]);
      }
      _ref1 = options.q;
      //Find parents without rows
      for (_j = 0, _len1 = _ref1.length; _j < _len1; _j++) {
        e = _ref1[_j];
        if (rfi[e[pid]] == null) {
          ri.push(e[id]);
        }
      }
      //Create the correct order
      while (ri.length) {
        thisid = ri.splice(0, 1);
        o.push(options.q[rfi[thisid]]);
        if (cfi[thisid] != null) {
          ri = cfi[thisid].concat(ri);
        }
      }
      return o;
    };

    //Transform a correctly sorted query (Use _queryTreeSort()) with id and beforeid into a tree object
    /* tree becomes nested like his:
      [
        {
          "id": 123,
          "before": 0,
          "name": "Tools",
          "children": [
            {
              "id": 456,
              "before": 123,
              "name": "drilling machine"
              "children": [
                {
                  "id": 214,
                  "before": 456,
                  "name": "drilling machine A"
                },
                { 
                  "id": 810,
                  "before": 456,
                  "name": "drilling machine B"
                },
                {
                  "id": 919,
                  "before": 456,
                  "name": "drilling machine C"
                }
              ]
            }
          ]
        }
      ] */
    var _makeTree = function(options) {
      var children, e, id, o, pid, temp, _i, _len, _ref;
      id = options.id || "id";
      pid = options.BeforeID || "BeforeID";
      children = options.children || "children";
      temp = {};
      o = [];
      _ref = options.q;
      //Create the tree
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        e = _ref[_i];
        e[children] = [];
        //Add the row to the index
        temp[e[id]] = e;
        //This parent should be in the index
        if (temp[e[pid]] != null) { //This row is a child?
          //Add the child to the parent
          temp[e[pid]][children].push(e);
        } else {
          //Add a root item
          o.push(e);
        }
      }
      return o;
    };