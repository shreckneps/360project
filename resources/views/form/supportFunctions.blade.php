
<datalist id="nameList"> </datalist>
<datalist id="attributeList"> </datalist>
<datalist id="featureList"> </datalist>
<datalist id="fieldList"> </datalist>

<datalist id="atroprs">
    <option value="eq">Equal To</option>
    <option value="ne">Not Equal To</option>
    <option value="lt">Less Than</option>
    <option value="gt">Greater Than</option>
</datalist>

<datalist id="ftroprs">
    <option value="eq">Equal To</option>
    <option value="ne">Not Equal To</option>
</datalist>

<script>
    var nameListValues = [];
    var attributeListValues = [];
    var featureListValues = [];
    var fieldListValues = [];

    function updateFieldHints(type, includePrice) {
        var toSend = "type=" + type;
        if(includePrice) {
            toSend += "&includePrice=yes";
        }

        $("#fieldList").html("");
        fieldListValues = [];

        $("#nameList").load("./ajax/nameList", toSend, function(data) {
            nameListValues = $("#nameList").children()
                .map(function() { return this.value; }).get();
        });

        $("#attributeList").load("./ajax/attributeList", toSend, function(data) {
            attributeListValues = $("#attributeList").children()
                .map(function() { return this.value; }).get();
            $("#fieldList").append($("#attributeList").children().clone());
            fieldListValues = fieldListValues.concat(attributeListValues);
        });

        $("#featureList").load("./ajax/featureList", toSend, function(data) {
            featureListValues = $("#featureList").children()
                .map(function() { return this.value; }).get();
            $("#fieldList").append($("#featureList").children().clone());
            fieldListValues = fieldListValues.concat(featureListValues);
        });
    }

    function genericFieldChangeCommon(id) {
        var newValue = $("#" + id).val();

        if(newValue == "") {
            $("#" + id + "val").prop("disabled", true).val("");
            $("#" + id + "type").val("");
            return;
        }

        if(fieldListValues.indexOf(newValue) == -1) {
            var alertText = "Warning: " + newValue;
            alertText += " not a valid feature name for this product category.";
            showAlert(alertText, "warning");
            $("#" + id + "val").prop("disabled", true);
            $("#" + id + "type").val("");
        } else {
            var newType;
            if(attributeListValues.indexOf(newValue) != -1) {
                newType = "atr";
                $("#" + id + "val").attr("type", "number");
                $("#" + id + "val").prop("required", true);
            } else {
                newType = "ftr";
                $("#" + id + "val").attr("type", "text");
                $("#" + id + "val").prop("required", false);
            }

            $("#" + id + "val").removeAttr("disabled");
            $("#" + id + "type").val(newType);
            var toSend = $("#" + id).parents("form").first().serialize() + "&numFld=" + numFields;
            toSend += "&name=" + newValue + "&table=" + newType + "&skipNum=" + id.substring(3);
            $("#" + id + "list").load("./ajax/fldValList", toSend);

            return newType;
        }
    }

    function genericFieldChange(id) {
        var newType = genericFieldChangeCommon(id);
        if(newType != null) {
            var newValue = $("#" + id).val();
            if(newValue != "") {
                var values = $(".field").map(function() { return this.value; }).get();

                if(values.indexOf(newValue) != values.lastIndexOf(newValue)) {
                    var alertText = "Warning: " + newValue;
                    alertText += " is a duplicate feature name.";
                    alertText += " Conflicting values will result in no products matching the search.";
                    showAlert(alertText, "warning");
                }
            }
        }
    }

    function genericFieldChangeComparison(id) {
        var newType = genericFieldChangeCommon(id);
        if(newType == "atr") {
            $("#" + id + "opr").html($("#atroprs").html());
        } else if(newType == "ftr") {
            $("#" + id + "opr").html($("#ftroprs").html());
        }
    }

    function addGeneric() {
        var toSend = "num=" + numFields++;
        $.get("./ajax/form/generic", toSend, function(data) {
            document.getElementById("recursiveBottom").outerHTML = data;
        });
    }

    function addGenericComparison() {
        var toSend = "num=" + numFields++;
        $.get("./ajax/form/genericComparison", toSend, function(data) {
            document.getElementById("recursiveBottom").outerHTML = data;
        });
    }

    function addFeature() {
        var toSend = "num=" + numFeatures;
        $.get("./ajax/form/feature", toSend, function(data) {
            document.getElementById("recursiveBottom").outerHTML = data;
            numFeatures++;
        });
    }

    function addAttribute() {
        var toSend = "num=" + numAttributes;
        $.get("./ajax/form/attribute", toSend, function(data) {
            document.getElementById("recursiveBottom").outerHTML = data;
            numAttributes++;
        });
    }

    function addComparison(type) {
        var toSend = "type=" + type + "&numAtr=" + numAttributes + "&numFtr=" + numFeatures;
        $.get("./ajax/cmpr", toSend, function(data) {
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

            var values = $(".field").map(function() { return this.value; }).get();
            if(values.indexOf(val) != values.lastIndexOf(val)) {
                var alertText = "Warning: " + val;
                alertText += " is a duplicate feature name.";
                alertText += " Only one value can be added for each feature name.";
                showAlert(alertText, "warning");
            }
            
            if(val == "Price") {
                var alertText = "Warning: Price cannot be used as a feature name.";
                showAlert(alertText, "warning");
            }
            if(type == "atr" && featureListValues.indexOf(val) != -1) {
                var alertText = "Warning: " + val;
                alertText += " is already in use as a descriptive feature name for this product category.";
                alertText += " It cannot be used as a numeric feature name."
                showAlert(alertText, "warning");
            }
            if(type == "ftr" && attributeListValues.indexOf(val) != -1) {
                var alertText = "Warning: " + val;
                alertText += " is already in use as a numeric feature name for this product category.";
                alertText += " It cannot be used as a descriptive feature name."
                showAlert(alertText, "warning");
            }

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
