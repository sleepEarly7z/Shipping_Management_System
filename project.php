<!--CPSC 304 Project by Yizhou Li, Haoliang Qi, Asuna Chen
    Adapted from CPSC 304 2021 WT1 Tutorial 7-->

    <html>
    <head>
        <title>CPSC 304 PHP/Oracle Demonstration</title>
    </head>

    <body>
        <h2>DROP TABLES EACH TIME</h2>
        <p>Please run drop.sql twice, and then run test.sql</p>

        <form method="POST" action="project.php">
            <!-- if you want another page to load after the button is clicked, you have to specify that page in the action parameter -->
            <input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
        </form>

        <hr />

        <h2>Insert Values into Stock_have</h2>
        <form method="POST" action="project.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
            StockItemID: <input type="text" name="insStockItemID"> <br /><br />
            Type: <input type="text" name="insType"> <br /><br />
            Quantity: <input type="text" name="insQuantity"> <br /><br />
            ItemID: <input type="text" name="insItemID"> <br /><br />

            <input type="submit" value="Insert" name="insertSubmit"></p>
        </form>

        <hr />

        <h2>Delete Values in Account</h2>
        <form method="POST" action="project.php"> <!--refresh page when submitted-->
            <input type="hidden" id="deleteQueryRequest" name="deleteQueryRequest">
            Login_name: <input type="text" name="insLogin_name"> <br /><br />
            <input type="submit" value="Insert" name="deleteSubmit"></p>
        </form>

        <hr />

        <h2>Update Quantity in Stock_have</h2>
        <p>The values are case sensitive and if you enter in the wrong case, the update statement will not do anything.</p>

        <form method="POST" action="project.php"> <!--refresh page when submitted-->
            <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
            StockItemID: <input type="text" name="name_StockItemID"> <br /><br />
            New Quantity: <input type="text" name="new_Quantity"> <br /><br />

            <input type="submit" value="Update" name="updateSubmit"></p>
        </form>

        <hr />

        <h2>Selection From</h2>
        <p>The values are case sensitive and if you enter in the wrong case, the update statement will not do anything.</p>

        <form method="GET" action="project.php"> <!--refresh page when submitted-->
            <input type="hidden" id="selectionQueryRequest" name="selectionQueryRequest">
            select: <input type="text" name="selectName"> <br /><br />
            from: <input type="text" name="fromName"> <br /><br />
            where: <input type="text" name="whereName"> <br /><br />

            <input type="submit" value="Submit" name="selectSubmit"></p>
        </form>
        

        <hr />

        <h2>Join </h2>
        <p>Please enter a number greater than zero, it will return the Stock Item ID less than the quantity</p>

        <form method="GET" action="project.php"> <!--refresh page when submitted-->
            <input type="hidden" id="joinQueryRequest" name="joinQueryRequest">

            Where Quantity < <input type="text" name="quantityName"> </p>

            <input type="submit" value="Submit" name="joinSubmit"></p>
        </form>

        <hr />

        <h2>Projection</h2>
        <p>The values are case sensitive and if you enter in the wrong case, the update statement will not do anything.</p>

        <form method="GET" action="project.php"> <!--refresh page when submitted-->
            <input type="hidden" id="projectionQueryRequest" name="projectionQueryRequest">
            Attribute1: <input type="text" name="attribute1"> <br /><br />
            Attribute2: <input type="text" name="attribute2"> <br /><br />
            Attribute3: <input type="text" name="attribute3"> <br /><br />
            From: <input type="text" name="fromName1"> <br /><br />
            Where: <input type="text" name="whereName1"> <br /><br />

            <input type="submit" value="Submit" name="projectSubmit"></p>
        </form>

        <hr />

        <h2>Division: Find DB Admin which control all products</h2>
        <form method="GET" action="project.php"> <!--refresh page when submitted-->
            <input type="hidden" id="divisionQueryRequest" name="divisionQueryRequest">
            <input type="submit" value="Submit" name="divideSubmit"></p>
        </form>

        <hr />

        <h2>Find the quantity and BackOrderID of the lowest backorder quantyity which is greater than 1 </h2>
        <form method="GET" action="project.php"> <!--refresh page when submitted-->
            <input type="hidden" id="groupbyQueryRequest" name="groupbyQueryRequest">
            <input type="submit" value="Submit" name="groupbySubmit"></p>
        </form>

        <hr />

        <h2>For each CurrentOrder with more than 1 Product, find the price of the cheapest product in the order </h2>
        <form method="GET" action="project.php"> <!--refresh page when submitted-->
            <input type="hidden" id="groupbyHavingQueryRequest" name="groupbyHavingQueryRequest">
            <input type="submit" value="Submit" name="groupbyHavingSubmit"></p>
        </form>

        <hr />

        <h2>Find the Maximum Quantity of BackOrder which is at least zero, for each BackOrder the average quantity is greater than zero is less than the average across all BackOrders </h2>
        <p>We want to find the BackOrder Quantity at least one but less than all BackOrder average quantity, so we can order more </p>
        <form method="GET" action="project.php"> <!--refresh page when submitted-->
            <input type="hidden" id="nestedQueryRequest" name="nestedQueryRequest">
            <input type="submit" value="Submit" name="nestedSubmit"></p>
        </form>

        <?php
		//this tells the system that it's no longer just parsing html; it's now parsing PHP

        $success = True; //keep track of errors so it redirects the page only if there are no errors
        $db_conn = NULL; // edit the login credentials in connectToDB()
        $show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

        function debugAlertMessage($message) {
            global $show_debug_alert_messages;

            if ($show_debug_alert_messages) {
                echo "<script type='text/javascript'>alert('" . $message . "');</script>";
            }
        }

        function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
            //echo "<br>running ".$cmdstr."<br>";
            global $db_conn, $success;

            $statement = OCIParse($db_conn, $cmdstr); 
            //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
                echo htmlentities($e['message']);
                $success = False;
            }

            $r = OCIExecute($statement, OCI_DEFAULT);
            if (!$r) {
                echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
                echo htmlentities($e['message']);
                $success = False;
            }

			return $statement;
		}

        function executeBoundSQL($cmdstr, $list) {
            /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
		In this case you don't need to create the statement several times. Bound variables cause a statement to only be
		parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection. 
		See the sample code below for how this function is used */

			global $db_conn, $success;
			$statement = OCIParse($db_conn, $cmdstr);

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn);
                echo htmlentities($e['message']);
                $success = False;
            }

            foreach ($list as $tuple) {
                foreach ($tuple as $bind => $val) {
                    //echo $val;
                    //echo "<br>".$bind."<br>";
                    OCIBindByName($statement, $bind, $val);
                    unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
				}

                $r = OCIExecute($statement, OCI_DEFAULT);
                if (!$r) {
                    echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                    $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
                    echo htmlentities($e['message']);
                    echo "<br>";
                    $success = False;
                }
            }
        }

        function printResult($result) { //prints results from a select statement
            echo "<br>Retrieved data from table:<br>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Name</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row["ID"] . "</td><td>" . $row["NAME"] . "</td></tr>"; //or just use "echo $row[0]" 
            }

            echo "</table>";
        }

        function connectToDB() {
            global $db_conn;

            // Your username is ora_(CWL_ID) and the password is a(student number). For example, 
			// ora_platypus is the username and a12345678 is the password.
            $db_conn = OCILogon("YOUR USERNAME", "YOUR PASSWORD", "dbhost.students.cs.ubc.ca:1522/stu");

            if ($db_conn) {
                debugAlertMessage("Database is Connected");
                return true;
            } else {
                debugAlertMessage("Cannot connect to Database");
                $e = OCI_Error(); // For OCILogon errors pass no handle
                echo htmlentities($e['message']);
                return false;
            }
        }

        function disconnectFromDB() {
            global $db_conn;

            debugAlertMessage("Disconnect from Database");
            OCILogoff($db_conn);
        }

        function handleUpdateRequest() {
            global $db_conn;

            $stockItemID = $_POST['name_StockItemID'];
            $quantity = $_POST['new_Quantity'];

            // you need the wrap the old name and new name values with single quotations
            executePlainSQL("UPDATE Stock_Have SET quantity='" . $quantity . "' WHERE StockItemID='" . $stockItemID . "'");
            OCICommit($db_conn);
        }

        function handleResetRequest() {
            global $db_conn;
            // Drop old table
            executePlainSQL("DROP TABLE demoTable");

            // Create new table
            echo "<br> creating new table <br>";
            executePlainSQL("CREATE TABLE demoTable (id int PRIMARY KEY, name char(30))");
            OCICommit($db_conn);
        }

        function handleInsertRequest() {
            global $db_conn;

            //Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['insStockItemID'],
                ":bind2" => $_POST['insType'],
                ":bind3" => $_POST['insQuantity'],
                ":bind4" => $_POST['insItemID']
            );

            $alltuples = array (
                $tuple
            );

            executeBoundSQL("insert into Stock_Have values (:bind1, :bind2,:bind3,:bind4)", $alltuples);
            OCICommit($db_conn);
        }

        function handleCountRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT Count(*) FROM demoTable");

            if (($row = oci_fetch_row($result)) != false) {
                echo "<br> The number of tuples in demoTable: " . $row[0] . "<br>";
            }
        }

        function handlePrintRequest(){
            global $db_conn;
            $result = executePlainSQL("SELECT * FROM Supply");
            printResult($result); 
        }

        function handleDeleteRequest(){  //need to fix this
            global $db_conn;

            $name = $_POST['insLogin_name'];
            echo "$name";
            // you need the wrap the old name and new name values with single quotations
            executePlainSQL("DELETE FROM Account WHERE Login_name='$name'");
            OCICommit($db_conn);
        }

        function handleSelectRequest() {
            global $db_conn;

            $selectName = $_GET['selectName'];
            $fromName = $_GET['fromName'];
            $whereName = $_GET['whereName'];
            $result = executePlainSQL("SELECT $selectName FROM $fromName WHERE $whereName");
            echo "<br>Retrieved data from table:<br>";
            echo "<table>";
            echo "<tr><th>"."$selectName"."</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td></tr>";
            }
            echo "</table>";
            OCICommit($db_conn);
            
        }

        function handleJoinRequest() {
            global $db_conn;

            $quantity = $_GET['quantityName'];
            $result = executePlainSQL("SELECT p.ItemID, p.name FROM Stock_have s, Product_take p WHERE p.StockItemID = s. StockItemID AND p.ItemID = s.ItemID AND s.Quantity < $quantity");
            echo "<br>Retrieved data from table Stock_have, Product_take:<br>";
            echo "<table>";
            echo "<tr><th>ItemID</th><th>Name</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>";
            }
            echo "</table>";
            //printResult($result); 
            // you need the wrap the old name and new name values with single quotations
            OCICommit($db_conn);
        }

        function handleProjectRequest() {
            global $db_conn;

            $attr1 = $_GET['attribute1'];
            $attr2 = $_GET['attribute2'];
            $attr3 = $_GET['attribute3'];
            $from1 = $_GET['fromName1'];
            $where1 = $_GET['whereName1'];
            $answer = [$attr1,$attr2,$attr3];
            $answer = implode(', ', array_filter($answer));
            $result = executePlainSQL("SELECT $answer FROM $from1 WHERE $where1");
            echo "<br>Retrieved data from table:" ."$from1"."<br>";
            echo "<table>";
            echo "<tr><th>"."$attr1"."</th><th>"."$attr2"."</th><th>"."$attr3"."</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>". $row[2] ."</td></tr>";
            }
            echo "</table>";
            // you need the wrap the old name and new name values with single quotations
            OCICommit($db_conn);
        }

        function handleGroupByRequest(){
            global $db_conn;
            $result = executePlainSQL("SELECT BOrderID, MIN(Quantity) FROM Backorder WHERE Quantity > 1 GROUP BY BOrderID");
            echo "<br>Retrieved data from table: BackOrder<br>";
            echo "BackOrderID <tr><td> Quantity";
            echo "<table>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>" . $row[2] . "</td><td>";
            }
            echo "</table>";
            OCICommit($db_conn);
        }
        
        function handleDivisionRequest(){
            global $db_conn;
            $result = executePlainSQL("SELECT UserID, Name
            FROM DBAdministrator_has d1
            WHERE NOT EXISTS ((SELECT ItemID FROM Product_take) MINUS (SELECT ItemID from Control c WHERE c.userID = d1.userID))");
            echo "<br>Retrieved data from table:<br>";
            echo "<table>";
            echo "<tr><th>UserID</th><th>Name</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>";
            }
            echo "</table>";
            OCICommit($db_conn);
        }
    
        function handleHavingRequest(){
            global $db_conn;
            $result = executePlainSQL("SELECT c.COrderID ,c.Quantity,MIN(p.Price) 
            FROM CurrentOrder c, Product_take p 
            WHERE c.COrderID = p.ItemID
            GROUP BY c.COrderID,c.Quantity
            HAVING c.Quantity>1");

            echo "<br>Retrieved data from table:<br>";
            echo "CurrentOrderID <tr><td> Quantity <tr><td> MIN price";
            echo "<br><br>";
            echo "<table>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>". $row[2] ;
            }
            echo "</table>";
            OCICommit($db_conn);
        }

        function handleNestedRequest(){
            global $db_conn;
            $result = executePlainSQL("SELECT MAX(Quantity), b1.BOrderID, avg(Quantity)
            FROM Supply s1, BackOrder b1
            WHERE (s1.orderID = b1.orderID AND b1.BOrderID = s1.BOrderID AND s1.StockItemID = b1.StockItemID AND Quantity > 0)
            GROUP BY b1.BOrderID
            HAVING avg(Quantity) < (SELECT avg(Quantity) From BackOrder)");


            echo "<br>Retrieved data from table:<br>";
            echo "<table>";
            echo "<tr><th>Max(Quantity)</th><th>BOrderID</th><th>AVG(Quantity)</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>"  . $row["MAX(QUANTITY)"] . "</td><td>" . $row[1] . "</td><td>". $row[2] ;
            }
            echo "</table>";
            OCICommit($db_conn);
        }


        // HANDLE ALL POST ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('resetTablesRequest', $_POST)) {
                    handleResetRequest();
                } else if (array_key_exists('updateQueryRequest', $_POST)) {
                    handleUpdateRequest();
                } else if (array_key_exists('insertQueryRequest', $_POST)) {
                    handleInsertRequest();
                } else if (array_key_exists('deleteQueryRequest', $_POST)) {
                    handleDeleteRequest();
                } 

                disconnectFromDB();
            }
        }

        // HANDLE ALL GET ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handleGETRequest() {
            if (connectToDB()) {
                if (array_key_exists('countTuples', $_GET)) {
                    handleCountRequest();
                } else if (array_key_exists('printTuples',$_GET)) {
                    handlePrintRequest();
                } else if (array_key_exists('selectSubmit',$_GET)) {
                    handleSelectRequest();
                } else if (array_key_exists('joinSubmit',$_GET)) {
                    handleJoinRequest();
                } else if (array_key_exists('projectSubmit',$_GET)) {
                    handleProjectRequest();
                } else if (array_key_exists('groupbySubmit',$_GET)) {
                    handleGroupByRequest();
                } else if (array_key_exists('divideSubmit',$_GET)) {
                    handleDivisionRequest();
                } else if (array_key_exists('groupbyHavingQueryRequest', $_GET)) {
                    handleHavingRequest();
                } else if (array_key_exists('nestedQueryRequest', $_GET)) {
                    handleNestedRequest();
                }
                disconnectFromDB();
            }
        }

		if (isset($_POST['reset']) || isset($_POST['updateSubmit']) || isset($_POST['insertSubmit']) || isset($_POST['deleteSubmit'])) {
            handlePOSTRequest();
        } else if (isset($_GET['countTupleRequest']) || isset($_GET['printTupleRequest']) || isset($_GET['selectionQueryRequest']) || isset($_GET['groupbyHavingQueryRequest'])
        || isset($_GET['joinQueryRequest'])  || isset($_GET['projectionQueryRequest']) || isset($_GET['groupbyQueryRequest']) || isset($_GET['divisionQueryRequest']) || isset($_GET['nestedQueryRequest'])) {
            handleGETRequest();
        }
		?>
	</body>
</html>
