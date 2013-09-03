<?php
/**
 * Default for sermons
 *
 * @package    BibleStudy.Site
 * @copyright  (C) 2007 - 2013 Joomla Bible Study Team All rights reserved
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.JoomlaBibleStudy.org
 * */
// No Direct Access
defined('_JEXEC') or die;
if (BIBLESTUDY_CHECKREL)
{
    JHtml::_('bootstrap.framework');
}
$JViewLegacy = new JViewLegacy;
$JViewLegacy->loadHelper('teacher');
$JBSMTeacher = new JBSMTeacher;
$teacher = $JBSMTeacher->getTeachersFluid($this->params);
$count = ($teacher['count']);
$teachers = $teacher['teachers'];
?>

<div class="container-fluid">
<div class="hero-unit">
    <div class="row-fluid">
        <div class="span4">
            <ul class="thumbnails">
                <?php $spans = 12 - $count;
                foreach ($teachers as $teach)
                {
                    foreach ($teach as $tea)
                    {dump($tea);
                        echo '<li class="span'.$spans.'">';
                        echo '<img class="thumbnail img-rounded" src="'.JURI::base().$tea->image.'">';
                        echo '<div class="caption"><p>'.$tea->name.'</p>';
                        echo '</li>';
                    }
                }
                ?>
                <img class="img-rounded" src="<?php echo JURI::base();?>tom.jpg">
                <div class="caption"><p>Pastor Tom Fuller</p></div>
            </ul>
        </div>
        <div class="span8">
            <h2>Bible Studies</h2>
            <p>At Calvary Chapel Newberg we go through the Bible, chapter by chapter, verse by verse. Our aim is to understand the history, culture, language, theology, and application of God's Word into our lives today. You'll find a lively and engaging format to every study!</p>
        </div>
    </div>
</div>
</div><!-- .hero-unit -->
<nav class="navbar">
    <div class="navbar-inner">
        <div class="container-fluid">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
<a class="brand" href="#">Home</a>
           <div class="nav-collapse">
                <!-- Link or button to toggle dropdown -->
                <ul class="nav nav-pull-right">
                    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">System</a>
                        <ul class="dropdown-menu ">
                            <li class=""><a data-toggle="dropdown" href="#">Another action1</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action2</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action3</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action4</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action5</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action6</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action1</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action2</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action3</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action4</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action5</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action6</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action1</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action2</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action3</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action4</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action5</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action6</a></li>
                        </ul>
                    </li>
                    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Books</a>
                        <ul class="dropdown-menu  ">
                            <li class=""><a data-toggle="dropdown" href="#">Another action1</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action2</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action3</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action4</a></li>
                            <li class="divider"></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action5</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action6</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action1</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action2</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action3</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action4</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action5</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action6</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action1</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action2</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action3</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action4</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action5</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action6</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action1</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action2</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action3</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action4</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action5</a></li>
                            <li class=""><a data-toggle="dropdown" href="#">Another action6</a></li>
                        </ul>
                    </li>
                </ul>

           </div>
        </div>

</nav>

<div class="container-fluid">

    <div class="row-fluid">
        <div class="span4">
            <h2>Box Number 1</h2>
            <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
            <p><a class="btn" href="#">Click meeee &raquo;</a></p>
        </div><!-- .span4 -->

        <div class="span4">
            <h2>Box Number 2</h2>
            <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
            <p><a class="btn" href="#">Click meeee &raquo;</a></p>
        </div><!-- .span4 -->

        <div class="span4">
            <h2>Box Number 3</h2>
            <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
            <p><a class="btn" href="#">Click meeee &raquo;</a></p>
        </div><!-- .span4 -->

    </div><!-- .row -->

    <div class="row-fluid ">
        <div class="span4"><div class=""><img src="<?php echo JURI::base();?>/tom.jpg"></div></div>
        <div class="span8">
            <div class="row-fluid ">
                <div class="span6"><div class="">2</div></div>
                <div class="span6"><div class="">3</div></div>
            </div>
            <div class="row-fluid ">
                <div class="span6"><div class="">4</div></div>
                <div class="span6"><div class="">5</div></div>
            </div>
        </div>
    </div>
    <div class="row-fluid ">
        <div class="span4">
            <div class="">6</div>
        </div>
        <div class="span4">
            <div class="">6</div>
        </div>
        <div class="span4">
            <div class="">6</div>
        </div>
    </div>
</div><!-- .container -->
</div>