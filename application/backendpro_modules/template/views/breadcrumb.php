<div id="breadcrumb">
<?php
// Loop over all breadcrumbs
for($i = 1; $i <= count($breadcrumbs); $i++)
{
    $breadcrumb = $breadcrumbs[$i - 1];

    // Don't display a link if no URI is given or if we are on the last item and aren't meant to
    if($breadcrumb['uri'] === FALSE || (!$display_final_link && $i == count($breadcrumbs)))
    {
        print $breadcrumb['title'];
    }
    else
    {
        print anchor($breadcrumb['uri'], $breadcrumb['title']);
    }

    // Display the separator if we are not on the last item
    if($i != count($breadcrumbs))
    {
        print $separator;
    }
}
?>
</div>