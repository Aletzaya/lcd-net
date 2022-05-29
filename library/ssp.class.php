<?php

/*
 * Helper functions for building a DataTables server-side processing SQL query
 *
 * The static functions in this class are just helper functions to help build
 * the SQL used in the DataTables demo server-side processing scripts. These
 * functions obviously do not represent all that can be done with server-side
 * processing, they are intentionally simple to show how it works. More complex
 * server-side processing operations will likely require a custom script.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

// Please Remove below 4 lines as this is use in Datatatables test environment for your local or live environment please remove it or else it will not work

class SSP {

    /**
     * Create the data output array for the DataTables rows
     *
     *  @param  array $columns Column information array
     *  @param  array $data    Data from the SQL get
     *  @return array          Formatted data in a row based format
     */
    static function data_output($columns, $data) {
        $out = array();

        for ($i = 0, $ien = count($data); $i < $ien; $i++) {
            $row = array();

            for ($j = 0, $jen = count($columns); $j < $jen; $j++) {
                $column = $columns[$j];

                // Is there a formatter?
                if (isset($column["formatter"])) {
                    if (empty($column["db"])) {
                        $row[$column["dt"]] = $column["formatter"]($data[$i]);
                    } else {
                        $row[$column["dt"]] = $column["formatter"]($data[$i][$column["db"]], $data[$i]);
                    }
                } else {
                    if (!empty($column["db"])) {
                        $row[$column["dt"]] = $data[$i][$columns[$j]["db"]];
                    } else {
                        $row[$column["dt"]] = "";
                    }
                }
            }

            $out[] = $row;
        }

        return $out;
    }

    /**
     * Database connection
     *
     * Obtain an PHP PDO connection from a connection details array
     *
     *  @return resource PDO connection
     */
    static function db() {
        return self::sql_connect($conn);
    }

    /**
     * Paging
     *
     * Construct the LIMIT clause for server-side processing SQL query
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @return string SQL limit clause
     */
    static function limit($request, $columns) {
        $limit = "";

        if (isset($request["iDisplayStart"]) && $request["iDisplayLength"] != -1) {
            $limit = "LIMIT " . intval($request["iDisplayStart"]) . ", " . intval($request["iDisplayLength"]);
        }

        return $limit;
    }

    /**
     * Ordering
     *
     * Construct the ORDER BY clause for server-side processing SQL query
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @return string SQL order by clause
     */
    static function order($request, $columns) {
        $order = "";

        $iColumns = explode(",", $request["sColumns"]);

        if (isset($request["iSortingCols"]) && count($iColumns) > 0) {
            $orderBy = array();

            for ($i = 0, $ien = count($iColumns); $i < $ien; $i++) {
                // Convert the column index into the column data property
                $columnIdx = intval($request["iSortCol_0"]);
                $requestColumn = $columns[$columnIdx];

                if ($request["bSortable_" . $i] == "true" && $request["iSortCol_0"] == $i) {
                    //error_log("columnIdx: $columnIdx " . print_r($column, true));
                    $dir = $request["sSortDir_0"] === "asc" ? "ASC" : "DESC";
                    $orderBy[] = "" . $requestColumn["db"] . " " . $dir;
                }
            }

            if (count($orderBy)) {
                $order = "ORDER BY " . implode(", ", $orderBy);
            }
            //error_log($order);
        }

        return $order;
    }

    /**
     * Searching / Filtering
     *
     * Construct the WHERE clause for server-side processing SQL query.
     *
     * NOTE this does not match the built-in DataTables filtering which does it
     * word by word on any field. It's possible to do here performance on large
     * databases would be very poor
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @param  array $bindings Array of values for PDO bindings, used in the
     *    sql_exec() function
     *  @return string SQL where clause
     */
    static function filter($request, $columns, &$bindings) {
        $globalSearch = array();
        $columnSearch = array();
        $dtColumns = self::pluck($columns, 'dt');

        $iColumns = explode(",", $request["sColumns"]);
        $str = $request["sSearch"];

        if (isset($request["sSearch"]) && $request["sSearch"] != "") {
            for ($i = 0, $ien = count($iColumns); $i < $ien; $i++) {
                $requestColumn = $columns[$i];

                if ($request["bSearchable_" . $i] === "true" && $str != "") {
                    if (!empty($requestColumn["db"])) {
                        $globalSearch[] = "" . $requestColumn["db"] . " LIKE '%$str%'";
                    }
                }
            }
        }

        // Individual column filtering
        if (isset($request["columns"])) {
            for ($i = 0, $ien = count($request["columns"]); $i < $ien; $i++) {
                $requestColumn = $request["columns"][$i];
                $columnIdx = array_search($requestColumn["data"], $dtColumns);
                $column = $columns[$columnIdx];

                $str = $requestColumn["search"]["value"];

                if ($requestColumn["searchable"] == "true" &&
                        $str != "") {
                    if (!empty($column["db"])) {
                        //$binding = self::bind($bindings, "%" . $str . "%", PDO::PARAM_STR);
                        $columnSearch[] = "" . $column["db"] . " LIKE " . $binding;
                    }
                }
            }
        }

        // Combine the filters into a single string
        $filter = "";

        if (count($globalSearch)) {
            $filter .= "AND (" . implode(" OR ", $globalSearch) . ")";
        }

        if (count($columnSearch)) {
            $filter .= " AND " . implode(" AND ", $columnSearch);
        }

        return $filter;
    }

    /**
     * The difference between this method and the simple one, is that you can
     * apply additional where conditions to the SQL queries. These can be in
     * one of two forms:
     *
     * * 'Result condition' - This is applied to the result set, but not the
     *   overall paging information query - i.e. it will not effect the number
     *   of records that a user sees they can have access to. This should be
     *   used when you want apply a filtering condition that the user has sent.
     * * 'All condition' - This is applied to all queries that are made and
     *   reduces the number of records that the user can access. This should be
     *   used in conditions where you don't want the user to ever have access to
     *   particular records (for example, restricting by a login id).
     *
     *  @param  array $request {
     *      @param string iDisplayStart Description iDisplayStart
     *            - Primer registro que debe mostrarse (utilizado para
     *            paginación).
     *      @param int iDisplayLength
     *            - La cantidad de registros que se deben devolver (este valor
     *            es igual al valor seleccionado en el menú desplegable 'Mostrar
     *            XXX elementos por página'). Este valor también se usa para la
     *            paginación.
     *      @param int iColumns
     *            - La cantidad de columnas en la tabla.
     *      @param string sSearch
     *            - Parametro devuelto por el campo de busqueda
     *      @param string iSortingCols
     *            - La cantidad de columnas utilizadas para ordenar. Por lo
     *            general, este valor es 1, pero si el usuario ordena resultados
     *            en más de una columna (manteniendo presionada la tecla MAYUS
     *            mientras hace clic en las celdas del encabezado), el
     *            complemento DataTables le envia información sobre el número
     *            de columnas que debe usar para ordenar los resultados.
     *      @param string iSortCol_0
     *            - Las identificaciones de las columnas utilizadas para ordenar
     *            los resultados. Si los resultados están ordenados por una
     *            columna, debe ordenar los resultados por la iSortCol_0
     *            columna.
     *      @param string sSortDir_0
     *            - La dirección de ordenación para cada una de las columnas
     *            utilizadas para el pedido. Si una columna ordena los
     *            resultados, se devolverá un valor "asc" o "desc" en el
     *            sSortDir_0 parámetro. En el caso del ordenamiento de múltiples
     *            columnas, cada parámetro en esta matriz tendrá una dirección
     *            que coincida con la columna en el iSortCol_parámetro.
     *      @param string sEcho
     *            - Valor entero utilizado por DataTables para fines de
     *            sincronización. La respuesta del código del servidor debería
     *            devolver el mismo valor al complemento.Data sent to server by DataTables
     *  }
     *  @param  string $table SQL table to query
     *  @param  string $primaryKey Primary key of the table
     *  @param  array $columns Column information array
     *  @param  string $whereResult WHERE condition to apply to the result set
     *  @return array          Server-side processing response array
     */
    static function complex($request, $table, $primaryKey, $columns, $additionalColumns = "", $whereResult = null) {
        $bindings = array();
        $db = self::db();
        
        // Build the SQL query string from the request
        $limit = self::limit($request, $columns);
        $order = self::order($request, $columns);
        $where = "WHERE TRUE " . self::filter($request, $columns, $bindings);

        if (true) {
            $whereResult = self::_flatten($whereResult);
        }

        if (!empty(trim($whereResult))) {
            $where .= " AND " . $whereResult;
        }

        // Main query to actually get the data
        $main_sql = "SELECT " . implode(", ", self::pluck($columns, "db")) . (!empty($additionalColumns) ? ", " . $additionalColumns : "") . " FROM $table $where $order $limit";
        //error_log($main_sql);
        $data = self::sql_exec($db, $bindings, $main_sql);

        // Data set length after filtering
        $resFilterLength = self::sql_exec($db, $bindings, "SELECT COUNT({$primaryKey}) FROM $table $where");
        $recordsFiltered = $resFilterLength[0][0];

        // Total data set length
        $resTotalLength = self::sql_exec($db, $bindings, "SELECT COUNT({$primaryKey}) FROM $table ");
        $recordsTotal = $resTotalLength[0][0];

        if (true):
            $iTotalRecords = $recordsTotal;
            $iTotalDisplayRecords = $recordsFiltered;
        endif;


        $jsonString["sEcho"] = $request["sEcho"];
        $jsonString["iTotalRecords"] = $iTotalRecords;
        $jsonString["iTotalDisplayRecords"] = $iTotalDisplayRecords;
        $jsonString["aoData"] = $data;

        return $jsonString;
    }

    /**
     * Connect to the database
     *
     * @return resource Database connection handle
     */
    static function sql_connect() {
        try {
            $pdo = new \com\softcoatl\utils\PDOConnection();
            $db = $pdo->getConnection();
        } catch (PDOException $e) {
            error_log($e);
            self::fatal("An error occurred while connecting to the database. " . "The error reported by the server was: " . $e->getMessage());
        }

        return $db;
    }

    /**
     * Execute an SQL query on the database
     *
     * @param  resource $db  Database handler
     * @param  array    $bindings Array of PDO binding values from bind() to be
     *   used for safely escaping strings. Note that this can be given as the
     *   SQL query string if no bindings are required.
     * @param  string   $sql SQL query to execute.
     * @return array         Result from the query (all rows)
     */
    static function sql_exec($db, $bindings, $sql = null) {
        // Argument shifting
        if ($sql === null) {
            $sql = $bindings;
        }
        //error_log($sql);
        $stmt = $db->prepare($sql);
        //echo $sql;
        // Bind parameters
        if (is_array($bindings)) {
            for ($i = 0, $ien = count($bindings); $i < $ien; $i++) {
                $binding = $bindings[$i];
                $stmt->bindValue($binding["key"], $binding["val"], $binding["type"]);
            }
        }

        // Execute
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            error_log($sql);
            self::fatal("An SQL error occurred: " . $e->getMessage());
        }

        // Return all
        return $stmt->fetchAll(PDO::FETCH_BOTH);
    }

    /*     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Internal methods
     */

    /**
     * Throw a fatal error.
     *
     * This writes out an error message in a JSON string which DataTables will
     * see and show to the user in the browser.
     *
     * @param  string $msg Message to send to the client
     */
    static function fatal($msg) {
        error_log("An SQL error occurred" . $msg);
        echo json_encode(array("error" => $msg));

        exit(0);
    }

    /**
     * Create a PDO binding key which can be used for escaping variables safely
     * when executing a query with sql_exec()
     *
     * @param  array &$a    Array of bindings
     * @param  *      $val  Value to bind
     * @param  int    $type PDO field type
     * @return string       Bound key to be used in the SQL where this parameter
     *   would be used.
     */
    static function bind(&$a, $val, $type) {
        $key = ":binding_" . count($a);
        $a[] = array("key" => $key, "val" => $val, "type" => $type);
        return $key;
    }

    /**
     * Pull a particular property from each assoc. array in a numeric array, 
     * returning and array of the property values from each item.
     *
     *  @param  array  $a    Array to get data from
     *  @param  string $prop Property to read
     *  @return array        Array of property values
     */
    static function pluck($a, $prop) {
        $out = array();

        for ($i = 0, $len = count($a); $i < $len; $i++) {
            if (empty($a[$i][$prop])) {
                continue;
            }
            //removing the $out array index confuses the filter method in doing proper binding,
            //adding it ensures that the array data are mapped correctly
            $out[$i] = $a[$i][$prop];
        }

        return $out;
    }

    /**
     * Return a string from an array or a string
     *
     * @param  array|string $a Array to join
     * @param  string $join Glue for the concatenation
     * @return string Joined string
     */
    static function _flatten($a, $join = " AND ") {
        if (!$a) {
            return "";
        } else if ($a && is_array($a)) {
            return implode($join, $a);
        }
        return $a;
    }

}
