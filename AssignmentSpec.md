This is a group project. After the mid-term, you should form a group of 3-5 members. You are allowed to pick your application/domain. Your final project MUST have the following components.

- A text collection (unstructured data) of a "reasonable" size (collected by a crawl). Aim for around 1,000 indexable documents. Do not go over by much as we have limited space on the server.
- A user interface with proper navigational tools so that even a naive user can utilize it with ease.
- A search facility that allows one to perform full-text search in the collection.
- A way to visualize the information (rank list, clusters, tag-cloud, etc.).
- A recommendation function based on logged interactions, a pre-trained model for related queries or documents, or even one created manually. More details were provided in the class.
- A mechanism to log all the user interactions. You can do this with a database, if you feel comfortable, or a simple text file.

You may **optionally** have

- Advanced search capabilities.
- Dynamic UI to enhance user experience.
- Interactive session support.
- Integration with a live web application.
- Clever use of CSS and JavaScript for validation and site configuration.
- Evaluation of processes/results with appropriate measures.

Finally, this should be the kind of work that you feel comfortable (and proud) demonstrating and listing on your portfolio. At the least, your project will be showcased (at your discretion) on the course website.

Your final submission should include all the source files, a link to the online working site, and a brief report documenting the project. This report should have

- **Introduction**: What is this project about and what it does/serves. (1/2 to 1 page) [5 points]
- **Design details**: Explain your decisions behind certain design choices (think about stemming, stopwords, retrieval models). May include figures. (1-2 pages) [15 points]
- **Usage scenario**: Sample queries, may include screenshots. (1-3 pages) [15 points]
- **Known issues and future work**: (1-2 pages) [10 points]
- **License**: An appropriate Creative Commons License is recommended. [5 points]
Make sure the report follows this format: PDF, 12pt Times family fonts, single-spacing, 1" margins. Anything that does not comply to this format may not be graded at all.

Grade rubric

- Working site - type the URL here
  - Professional presentation of the site and the information (results, recommendations, etc.) [10 points]
  - Effectiveness of the recommendations [5 points]
  - Ease of use of the interface [5 points]
  - Functionality (does it serve what it says in the description?) [10 points]
- Project report [50 points] - attach a pdf file
Make sure you put the link to your working site right here. Your working project must be hosted on the class server.


**Additional Comment**
Final project - recommendation/personalization help
Folks,

I know at least some of you are trying to do some sophisticated recommendations/personalizations with your projects and running into coding issues. As we discussed in the class, you don't need all that for this project. You just have to log the queries and somehow use those queries for recommendation. It's quite easy to do.

Here's how you log your queries:
file_put_contents("logs.txt", $search_string.PHP_EOL, FILE_APPEND | LOCK_EX);

That's right. Just one line of code in your search.php will do the trick. Now all your queries will be stored in logs.txt file. If you want to see this line in actual code, find it at /var/www/html/chirags/search.php

How do I use logs.txt to find the top queries? UNIX makes it easy. You can run the following command to test:

sort logs.txt | uniq -c

Assuming you have several entries in your logs.txt, with some of them repeating, you will see an output here with frequency of each query. You can now parse this file to extract the top queries.

To run the above command through search.php, just use exec. So something like
exec("sort logs.txt | uniq -c > top_queries.txt")

will get the output in top_queries.txt file. You can open that file for reading inside your PHP code.

So once again, we are looking for simple functionality here and I almost gave you full solution above!
