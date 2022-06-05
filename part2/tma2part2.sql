-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 05, 2022 at 03:56 AM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 8.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tma2part2`
--

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `Image Id` int(11) NOT NULL,
  `Image Name` varchar(200) NOT NULL,
  `Image` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`Image Id`, `Image Name`, `Image`) VALUES
(2, 'Fig_1_6.png', '../../media/Fig_1_6.png');

-- --------------------------------------------------------

--
-- Table structure for table `lesson description`
--

CREATE TABLE `lesson description` (
  `Course ID` int(11) NOT NULL,
  `Description` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lesson description`
--

INSERT INTO `lesson description` (`Course ID`, `Description`) VALUES
(1, 'Introduction to the Web Development Course.'),
(2, 'A brief overview of what languages the course will cover. '),
(3, 'A demo quiz to test the EML Document generator.');

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `Course ID` int(11) NOT NULL,
  `EML Doc` longtext NOT NULL,
  `Name` mediumtext NOT NULL,
  `Subject` tinytext NOT NULL,
  `Type` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`Course ID`, `EML Doc`, `Name`, `Subject`, `Type`) VALUES
(1, '<lesson>\r\n<title>1 Introduction to Computers and the Internet</title>\r\n<subTitle>1.1 Introduction</subTitle>\r\n<content>\r\n<par>Welcome to the exciting and rapidly evolving world of Internet and web programming! There are more than two billion Internet users worldwide—that’s approximately 30% of the Earth’s population. In use today are more than a billion general-purpose computers, and billions more embedded computers are used in cell phones, smartphones, tablet computers, home appliances, automobiles and more—and many of these devices are connected to the Internet. According to a study by Cisco Internet Business Solutions Group, there were 12.5 billion Internet-enabled devices in 2010, and the number is predicted to reach 25 billion by 2015 and 50 billion by 2020. The Internet and web programming technologies you’ll learn in this book are designed to be portable, allowing you to design web pages and applications that run across an enormous range of Internet-enabled devices. </par>\r\n\r\n<par>You’ll begin by learning the client-side programming technologies used to build web pages and applications that are run on the client (i.e., in the browser on the user’s device). You’ll use <italic>HyperText Markup Language 5</italic> (HTML5) and <italic>Cascading Style Sheets 3</italic> (CSS3)—the recent releases of HTML and CSS technologies—to add powerful, dynamic and fun features and effects to web pages and web applications, such as audio, video, animation, drawing, image manipulation, designing pages for multiple screen sizes, access to web storage and more. You’ll learn <italic>JavaScript</italic>—the language of choice for implementing the client side of Internet-based applications (we discuss JavaScript in more detail in Section 1.3). Chapters 6–13 present rich coverage of JavaScript and its capabilities. You’ll also learn about jQuery—the JavaScript library that’s dramatically reshaping the world of web development. Throughout the book there’s also an emphasis on Ajax development, which helps you create better-performing, more usable applications. <external> www.internetworldstats.com/stats.htm.2. www.cisco.com/web/about/ac79/docs/innov/IoT_IBSG_0411FINAL.pdf </external> .Later in the book, you’ll learn server-side programming—the applications that respond to requests from client-side web browsers, such as searching the Internet, checking your  bank-account balance, ordering a book from Amazon, bidding on an eBay auction and ordering concert tickets. We present condensed treatments of four popular Internet/web programming languages for building the server side of Internet- and web-based client/server applications. Chapters 19–22 and 23–28 present three popular server-side technologies, including PHP, ASP.NET (in both C# and Visual Basic) and JavaServer Faces. Be sure to read both the Preface and the Before You Begin section to learn about the book’s coverage and how to set up your computer to run the hundreds of code examples. The code is available at <external>www.deitel.com/books/iw3htp5</external> and <external>www.pearsonhighered.com/deitel </external>. Use the source code we provide to run every program and script as you study it. Try each example in multiple browsers. If you’re interested in smartphones and tablet computers, be sure to run the examples in your browsers on iPhones, iPads, Android smartphones and tablets, and others. The technologies covered in this book and browser support for them are evolving rapidly. Not every feature of every page we build will render properly in every browser. All seven of the browsers we use are free. </par>\r\n</content>\r\n\r\n<content>\r\n<topic>Moore’s Law</topic>\r\n<par>Every year, you probably expect to pay at least a little more for most products and services. The opposite has been the case in the computer and communications fields, especially with regard to the costs of hardware supporting these technologies. For many decades, hardware costs have fallen rapidly. Every year or two, the capacities of computers have approximately doubled inexpensively. This remarkable trend often is called <def><bold>Moore’s Law</bold></def>, named for the person who identified it, Gordon Moore, co-founder of Intel—the leading manufacturer of the processors in today’s computers and embedded systems. Moore’s Law and related observations apply especially to the amount of memory that computers have for programs, the amount of secondary storage (such as disk storage) they have to hold programs and data over longer periods of time, and their processor speeds—the speeds at which computers execute their programs (i.e., do their work). Similar growth has occurred in the communications field, in which costs have plummeted as enormous demand for communications bandwidth (i.e., information-carrying capacity) has attracted intense competition. We know of no other fields in which technology improves so quickly and costs fall so rapidly. Such phenomenal improvement is truly fostering the Information Revolution.</par>\r\n</content>\r\n</lesson>', '1.1 Introduction', 'Web Development', 'Lesson'),
(2, '<lesson>\r\n<subTitle>1.3 HTML5, CSS3, JavaScript, Canvas and jQuery</subTitle>\r\n<content>\r\n<topic>HTML5</topic>\r\n<par><def><bold>HTML (HyperText Markup Language) </bold></def>—a special type of computer language called a markup language designed to specify the content and structure of web pages (also called documents) in a portable manner. HTML5, now under development, is the emerging version of HTML. HTML enables you to create content that will render appropriately across the extraordinary range of devices connected to the Internet—including smartphones, tablet computers, notebook computers, desktop computers, special-purpose devices such as large-screen displays at concert arenas and sports stadiums, and more. </par>\r\n\r\n<par>You’ll learn the basics of HTML5, then cover more sophisticated techniques such as creating tables, creating forms for collecting user input and using new features in HTML5, including page-structure elements that enable you to give meaning to the parts of a page (e.g., headers, navigation areas, footers, sections, figures, figure captions and more). A “stricter” version of HTML called <bold>XHTML (Extensible HyperText Markup Language)</bold>, which is based on XML (eXtensible Markup Language, introduced in Chapter 15), is still used frequently today. Many of the server-side technologies we cover later in the book produce web pages as XHTML documents, by default, but the trend is clearly to HTML5.</par>\r\n</content>\r\n<content>\r\n<topic>Cascading Style Sheets (CSS)</topic>\r\n<par>Although HTML5 provides some capabilities for controlling a document’s presentation, it’s better not to mix presentation with content. HTML5 should be used only to specify a document’s structure and content. Chapters 4–5 use <bold>Cascading Style Sheets (CSS)</bold> to specify the presentation, or styling, of elements on a web page (e.g., fonts, spacing, sizes, colors, positioning). CSS was designed to style portable web pages independently of their content and structure. By separating page styling from page content and structure, you can easily change the look and feel of the pages on an entire website, or a portion of a website, simply by swapping out one style sheet for another. CSS3 is the current version of CSS under development. Chapter 5 introduces many new features in CSS3. </par>\r\n</content>\r\n<content>\r\n<topic>JavaScript</topic>\r\n<par><def><bold>JavaScript</bold></def> is a language that helps you build dynamic web pages (i.e., pages that can be modified “on the fly” in response to events, such as user input, time changes and more) and computer applications. It enables you to do the client-side programming of web applications. In addition, there are now several projects dedicated to server-side JavaScript, including CommonJS (www.commonjs.org), Node.js (nodejs.org) and Jaxer (jaxer.org).JavaScript was created by Netscape, the company that built the first wildly successful web browser. Both Netscape and Microsoft have been instrumental in the standardization of JavaScript by ECMA International (formerly the European Computer Manufacturers Association) as ECMAScript. ECMAScript 5, the latest version of the standard, corresponds to the version of JavaScript we use in this book.The JavaScript chapters of the book are more than just an introduction to the language. They also present computer-programming fundamentals, including control structures, functions, arrays, recursion, strings and objects. You’ll see that JavaScript is a portable scripting language and that programs written in JavaScript can run in web browsers across a wide range of devices. </par>\r\n</content>\r\n<content>\r\n<topic>Web Browsers and Web-Browser Portability</topic>\r\n<par>Ensuring a consistent look and feel on client-side browsers is one of the great challenges of developing web-based applications. Currently, a standard does not exist to which software vendors must adhere when creating web browsers. Although browsers share a common set of features, each browser might render pages differently. Browsers are available in many versions and on many different platforms (Microsoft Windows, Apple Macintosh, Linux, UNIX, etc.). Vendors add features to each new version that sometimes result in cross-platform incompatibility issues. It’s difficult to develop web pages that render correctly on all versions of each browser.All of the code examples in the book were tested in the five most popular desktop browsers and the two most popular mobile browsers (Fig. 1.5). Support for HTML5, CSS3 and JavaScript features varies by browser. </par>\r\n<par>The HTML5 Test website (<external link=\"http://html5test.com/\">http://html5test.com/</external>) scores each browser based on its support for the latest features of these  evolving standards. Figure 1.5 lists the five desktop browsers we use in reverse order of their HTML5 Test scores from most compliant to least compliant at the time of this writing. Internet Explorer 10 (IE10) is expected to have a much higher compliance rating than IE9. You can also check sites such as http://caniuse.com/ for a list of features covered by each browser. </par>\r\n</content>\r\n<content>\r\n<topic>Validating Your HTML5, CSS3 and JavaScript Code</topic>\r\n<par>As you’ll see, JavaScript programs typically have HTML5 and CSS3 portions as well. You must use proper HTML5, CSS3 and JavaScript syntax to ensure that browsers process your documents properly. Figure 1.6 lists the validators we used to validate the code in this book. Where possible, we eliminated validation errors.3.<external link=”www.activoinc.com/blog/2008/11/03/jquery-emerges-as-most-popular-javascriptlibrary-for-web-development/”> www.activoinc.com/blog/2008/11/03/jquery-emerges-as-most-popular-javascriptlibrary-for-web-development/. </external>\r\n</par>\r\n<image>\r\n<name>Fig_1_6.png</name>\r\n</image>\r\n</content>\r\n</lesson>\r\n', '1.3 HTML5, CSS3, JavaScript, Canvas and jQuery', 'Web Development', 'Lesson'),
(3, '<quiz>\r\n<title>This is a demo Quiz</title>\r\n<instruction>This is a demonstration of the quizzes that this website can conduct. This is a multiple choice quiz. You will be given a question and four options. Read the question carefully and pick the best one. At the end click on submit to see the result.</instruction>\r\n<question>\r\n<ask>Is this a demo?</ask>\r\n<choice>Yes</choice>\r\n<choice>No</choice>\r\n<choice>No</choice>\r\n<choice>No</choice>\r\n<answer>A</answer>\r\n</question>\r\n</quiz>\r\n', 'Demo Quiz', 'N/A', 'Quiz');

-- --------------------------------------------------------

--
-- Table structure for table `lessons registered`
--

CREATE TABLE `lessons registered` (
  `Course ID` int(11) NOT NULL,
  `Student ID` int(11) NOT NULL,
  `Enrolled` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lessons registered`
--

INSERT INTO `lessons registered` (`Course ID`, `Student ID`, `Enrolled`) VALUES
(1, 1, '2022-06-05'),
(2, 1, '2022-06-05'),
(3, 1, '2022-06-05');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `Age` smallint(6) NOT NULL,
  `email` varchar(75) NOT NULL,
  `firstName` varchar(60) NOT NULL,
  `ID` int(11) NOT NULL,
  `lastName` varchar(60) NOT NULL,
  `password` mediumtext NOT NULL,
  `userName` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`Age`, `email`, `firstName`, `ID`, `lastName`, `password`, `userName`) VALUES
(18, 'test@test.com', 'test', 1, 'tester', '$2y$10$aGSpYqO4W1WQ/.jS2CNRfOVOq7dLgvzA6PSXiFJ7zFpId/QpidBTO', 'mog');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`Image Id`);

--
-- Indexes for table `lesson description`
--
ALTER TABLE `lesson description`
  ADD KEY `Course ID2` (`Course ID`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`Course ID`);

--
-- Indexes for table `lessons registered`
--
ALTER TABLE `lessons registered`
  ADD PRIMARY KEY (`Course ID`,`Student ID`),
  ADD KEY `ID` (`Student ID`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `Image Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `Course ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `lesson description`
--
ALTER TABLE `lesson description`
  ADD CONSTRAINT `Course ID2` FOREIGN KEY (`Course ID`) REFERENCES `lessons` (`Course ID`);

--
-- Constraints for table `lessons registered`
--
ALTER TABLE `lessons registered`
  ADD CONSTRAINT `Course ID` FOREIGN KEY (`Course ID`) REFERENCES `lessons` (`Course ID`),
  ADD CONSTRAINT `ID` FOREIGN KEY (`Student ID`) REFERENCES `students` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
