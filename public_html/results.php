<html>
        <body onload="initialize()" onunload="GUnload()">
                <form>
                        <div class="label">Location</div>
                        <input type="text" name="location" value="<?php echo $_GET["location"] ?>"><br>
                        <div class="label">Keyword</div>
                        <input type="text" name="keyword" value="<?php echo $_GET["keyword"] ?>"><br>
                        <div class="label">Salary</div>
                        <input type="text" name="salary" value="<?php echo $_GET["salary"] ?>"><br>
                        <div class="label">Education</div>
                        <input type="text" name="education" value="<?php echo $_GET["education"] ?>"><br>
                        <input type="submit" value="Redo"><br>
                </form>
                <script type="text/javascript">
                        function initialize() {
                                if (GBrowserIsCompatible()) {
                                        var map = new GMap2(document.getElementById("map_canvas");
                                        map.setCenter(new GLatLng(0, 0),13);
                                        map.setUIToDefault();
                                }
                        }
                </script>
                <div id="map_canvas" style="width: 500px; height: 300px"></div>
        </body>
</html>
