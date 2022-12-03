
<datalist id="nameList"> </datalist>
<datalist id="attributeList"> </datalist>
<datalist id="featureList"> </datalist>

<script>

    function updateFieldHints(type, includePrice) {
        var toSend = "type=" + type;
        if(includePrice) {
            toSend += "&includePrice=yes";
        }
        $("#nameList").load("./ajax/nameList", toSend);
        $("#attributeList").load("./ajax/attributeList", toSend);
        $("#featureList").load("./ajax/featureList", toSend);
    }

    function addFeature() {
        var toSend = "num=" + numFeatures;
        $.get("./ajax/form/ftr", toSend, function(data) {
            document.getElementById("recursiveBottom").outerHTML = data;
            numFeatures++;
        });
    }

    function addAttribute() {
        var toSend = "num=" + numAttributes;
        $.get("./ajax/form/atr", toSend, function(data) {
            document.getElementById("recursiveBottom").outerHTML = data;
            numAttributes++;
        });
    }

    function addComparison(type) {
        var toSend = "type=" + type + "&numAtr=" + numAttributes + "&numFtr=" + numFeatures;
        $.get("./ajax/form/cmpr", toSend, function(data) {
            document.getElementById("recursiveBottom").outerHTML = data;
            if(type == "atr") {
                numAttributes++;
            } else {
                numFeatures++;
            }
        });
    }

    function fieldChange(id) {
        var val = document.getElementById(id).value;
        var type = id.slice(0, 3);
        var num = id.slice(3);
        if(val != "") {
            document.getElementById(type + "val" + num).disabled = false;

            var toSend = "name=" + val + "&type=" + document.getElementById("typeInput").value;
            $("#" + type + 'val' + num + "list").load("./ajax/" + type + "ValList", toSend);
        } else {
            document.getElementById(type + "val" + num).disabled = true;
        }
    }

    function promoteField() {
        var row = $(event.target).parents("tr")[0];
        var num = parseInt(row.id.slice(3));
        if(num > 0) {
            var beforeChildren = $("#row" + (num - 1)).children().detach();
            var currentChildren = $("#row" + num).children().detach();
            $("#row" + (num - 1)).append(currentChildren);
            $("#row" + num).append(beforeChildren);
            
            var swap = document.getElementById("fld" + (num - 1)).value;
            document.getElementById("fld" + (num - 1)).value = document.getElementById("fld" + num).value; 
            document.getElementById("fld" + num).value = swap;
        }
    }

</script>
