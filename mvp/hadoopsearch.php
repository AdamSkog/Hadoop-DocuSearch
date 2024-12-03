<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hadoop Documentation Search</title>
    <style>
        /* [Existing CSS styles] */
        body {
            font-family: Arial, sans-serif;
            background-color: #001f3f;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        header {
            background: #333;
            color: #fff;
            padding-top: 30px;
            min-height: 70px;
            border-bottom: #77aaff 3px solid;
        }
        header a {
            color: #fff;
            text-decoration: none;
            text-transform: uppercase;
            font-size: 16px;
        }
        header ul {
            padding: 0;
            list-style: none;
        }
        header li {
            float: left;
            display: inline;
            padding: 0 20px 0 20px;
        }
        header #branding {
            float: left;
        }
        header #branding h1 {
            margin: 0;
        }
        header nav {
            float: right;
            margin-top: 10px;
        }
        #showcase {
            min-height: 400px;
            text-align: center;
            color: #fff;
        }
        #showcase h1 {
            margin-top: 100px;
            font-size: 55px;
            margin-bottom: 10px;
        }
        #showcase p {
            font-size: 20px;
        }
        .search-box {
            margin: 20px 0;
            text-align: center;
        }
        .search-box input[type="text"] {
            padding: 10px;
            font-size: 18px;
            border: 1px solid #ccc;
            width: 300px;
        }
        .search-box input[type="submit"] {
            padding: 10px 20px;
            font-size: 18px;
            background: #333;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .search-box input[type="submit"]:hover {
            background: #77aaff;
        }
        .results {
            margin: 20px 0;
            background: #fff;
            padding: 10px;
            border: 1px solid #ccc;
        }
        .results a {
            display: block;
            padding: 10px;
            background: #fff;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            text-decoration: none;
            color: #333;
        }
        .results a:hover {
            background: #f4f4f4;
        }
        .recommendations {
            margin: 20px 0;
            background: #fff;
            padding: 10px;
            border: 1px solid #ccc;
        }
        .recommendations h2 {
            margin-top: 0;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div id="branding">
                <h1>Hadoop Documentation Search</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="#">Home</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section id="showcase">
        <div class="container">
            <h1>Search The Hadoop Index!</h1>
            <p>Explore the Hadoop documentation with this simple tool. Learn about how to use Hadoop and its ecosystem!</p>
            <a title="Apache Software Foundation, Apache License 2.0 &lt;http://www.apache.org/licenses/LICENSE-2.0&gt;, via Wikimedia Commons" href="https://commons.wikimedia.org/wiki/File:Hadoop_logo.svg"><img width="512" alt="Hadoop logo" src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/0e/Hadoop_logo.svg/512px-Hadoop_logo.svg.png?20130221043911"></a>
            <p>Example Queries: <em>mapreduce, hdfs, yarn, hive</em></p>
        </div>
    </section>

    <div class="container">
        <div class="search-box">
            <form action="hadoopsearch.php" method="post">
                <input type="text" name="search_string" placeholder="Enter hadoop term" value="<?php echo isset($_POST["search_string"]) ? htmlspecialchars($_POST["search_string"]) : ''; ?>"/>
                <input type="submit" value="Search"/>
            </form>
        </div>

        <div class="results">
            <?php
                if (isset($_POST["search_string"])) {
                    $search_string = trim($_POST["search_string"]);
                    
                    // Log the search query to logs.txt
                    file_put_contents("logs.txt", $search_string.PHP_EOL, FILE_APPEND | LOCK_EX);

                    // Create and write to query.py
                    $qfile = fopen("query.py", "w");

                    fwrite($qfile, "import pyterrier as pt\nif not pt.started():\n\tpt.init()\n\n");
                    fwrite($qfile, "import pandas as pd\nqueries = pd.DataFrame([[\"q1\", \"$search_string\"]], columns=[\"qid\",\"query\"])\n");
                    fwrite($qfile, "index = pt.IndexFactory.of(\"./hadoopindex/\")\n");
                    fwrite($qfile, "bm25 = pt.BatchRetrieve(index, wmodel=\"BM25\")\n");
                    fwrite($qfile, "results = bm25.transform(queries)\n");

                    fwrite($qfile, "for i in range(5):\n");
                    fwrite($qfile, "    docid = results.docid[i]\n");
                    fwrite($qfile, "    title = index.getMetaIndex().getItem('title', docid).strip()\n");
                    fwrite($qfile, "    if title != \"\":\n");
                    fwrite($qfile, "        print(title)\n");
                    fwrite($qfile, "    else:\n");
                    fwrite($qfile, "        print(index.getMetaIndex().getItem('filename', docid).strip())\n");
                    // Print top 5 results
                    // for ($i=0; $i<5; $i++) {
                        // fwrite($qfile, "title = index.getMetaIndex().getItem(\"title\", results.docid[$i]).strip()\n");
                        // fwrite($qfile, "print(title)\n");
                    //     fwrite($qfile, "print(index.getMetaIndex().getItem(\"filename\",results.docid[$i]))\n");
                    //     fwrite($qfile, "if index.getMetaIndex().getItem(\"title\", results.docid[$i]).strip() != \"\":\n");
                    //     fwrite($qfile, "\tprint(index.getMetaIndex().getItem(\"title\",results.docid[$i]))\n");
                    //     fwrite($qfile, "else:\n\tprint(index.getMetaIndex().getItem(\"filename\",results.docid[$i]))\n");
                    // }
                
                    fclose($qfile);

                    // Execute the Python search script via UDP
                    exec("ls | nc -u 127.0.0.1 10013");
                    sleep(3);

                    $stream = fopen("output", "r");

                    echo "<h2>Search Results:</h2>";
                    while(($line=fgets($stream))!=false) {
                        $clean_line = preg_replace('/\s+/',',',$line);
                        $record = explode("./", $clean_line);
                        $line = fgets($stream);
                        echo "<a href=\"http://$record[1]\">".$line."</a><br/>\n";
                    }

                    fclose($stream);
                
                    // Clean up temporary files
                    exec("rm query.py");
                    exec("rm output");
                }
            ?>
        </div>

        <div class="recommendations">
            <?php
                // Generate recommendations based on logs.txt
                $log_file = "logs.txt";

                if (file_exists($log_file)) {
                    // Execute UNIX commands to get top queries
                    exec("sort $log_file | uniq -c | sort -nr | head -5", $top_queries);

                    if (!empty($top_queries)) {
                        echo "<h2>Other Users Searched For:</h2>";
                        foreach ($top_queries as $top_query) {
                            // Each line has the format: count query
                            $parts = preg_split('/\s+/', trim($top_query), 2);
                            if (count($parts) == 2) {
                                $count = htmlspecialchars($parts[0]);
                                $query = htmlspecialchars($parts[1]);
                                echo "<p><strong>$query</strong> - $count searches</p>\n";
                            }
                        }
                    } else {
                        echo "<p>No recommendations available.</p>";
                    }
                } else {
                    echo "<p>No search logs found.</p>";
                }
            ?>
        </div>
    </div>
</body>
</html>