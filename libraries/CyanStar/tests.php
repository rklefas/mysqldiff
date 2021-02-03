<?php


$classfiles = glob('*/*.php');

$classbefore = get_declared_classes();


foreach ($classfiles as $cfile)
	require_once $cfile;
	
$classafter = get_declared_classes();


$diff = array_diff($classafter, $classbefore);



$array['fruit'] = "apple";
$array['place'] = "New York";
$array['person'] = "my brother from another mother";
$array['company'] = "Zenith";

$arrayTwo['fruit'] = "APPLE";
$arrayTwo['place'] = "New York, NY";
$arrayTwo['person'] = "my brother";



$earray['empty'] = "";

$starter = "I am going to buy a [fruit] with [person] in [place]";

$outputs['LibStrings::wordCount'][] = LibStrings::wordCount("a tree in the wind?  i say not. ");
$outputs['LibStrings::wordCount'][] = LibStrings::wordCount("what?!??!?!  aaaaahhhhhhhh... . . . . . . ???? !");
$outputs['LibStrings::trimWord'][] = LibStrings::trimWord("a tree in the wind ", "wind");
$outputs['LibStrings::trimWord'][] = LibStrings::trimWord(" wind, window, tree wind wind wind", "wind");

$outputs['LibStrings::oneWithinOther'][] = LibStrings::oneWithinOther("a tree in the wind", "wind");
$outputs['LibStrings::oneWithinOther'][] = LibStrings::oneWithinOther("wind", "a tree in the wind");
$outputs['LibStrings::oneWithinOther'][] = LibStrings::oneWithinOther("apple", "a tree in the wind");
$outputs['LibStrings::spaceByCase'] = LibStrings::spaceByCase("SpaceByCaseVersion1");
$outputs['LibStrings::renderStringWithArray'] = LibStrings::renderStringWithArray($starter, $array);
$outputs['LibStrings::writtenImplode'] = LibStrings::writtenImplode($array, "or");
$outputs['LibStrings::truncate'] = LibStrings::truncate($starter, 15, " [more to follow]");
$outputs['DataMining::extractResources'] = DataMining::extractResources("FREE HAIR CUT & STYLE WITH PURCHASE OF ANY COLOR SERVICES

http://www.migdigitizing.com
https://www.migdigitizing.com
ftp://www.migdigitizing.com
https://www.youtube.com/watch?v=Zn_MF3mjRnQ&feature=g-high-u


HAIRPIN SALON
10120 YONGE ST.
RICHMOND HILL

http://www.youtube.com/watch?v=YlCoG6fdAu0

905-883-7803
www.hairpinsalon.com
(905)883-7808


http://www.youtube.com/watch?v=YlCoG6fdAuw");
$outputs['Arrays::randomIndex'] = Arrays::randomIndex($array);
$outputs['Arrays::expandNumericalRanges'][] = Arrays::expandNumericalRanges("2, 4, 7:12, 15:16, 22, 20");
$outputs['Arrays::expandNumericalRanges'][] = Arrays::expandNumericalRanges("1:3, 5,6,7,9,");
$outputs['Arrays::expandNumericalRanges'][] = Arrays::expandNumericalRanges("1:12, 9");
$outputs['Arrays::getByNumericIndex'][] = Arrays::getByNumericIndex($array, 1);
$outputs['Arrays::getByNumericIndex'][] = Arrays::getByNumericIndex($array, -1);
$outputs['Arrays::optimalMerge'][] = Arrays::optimalMerge($array, $arrayTwo);
$outputs['Colors::generate'] = Colors::generate();
$outputs['Arrays::removeEmptyElements'] = Arrays::removeEmptyElements( array_merge($earray, $array) );
$outputs['DataTypeConversion::encode_xml'][] = DataTypeConversion::encode_xml( array_merge($earray, $array) );
$outputs['DataTypeConversion::decode_xml'][] = DataTypeConversion::decode_xml( DataTypeConversion::encode_xml( array_merge($earray, $array) ) );
$outputs['DataTypeConversion::encode_xml'][] = DataTypeConversion::encode_xml( ' string ' );
$outputs['DataTypeConversion::decode_xml'][] = DataTypeConversion::decode_xml( DataTypeConversion::encode_xml( ' string ' ) );
$outputs['DataTypeConversion::ArrayToObject'] = DataTypeConversion::ArrayToObject($arrayTwo);
$outputs['DataTypeConversion::arrayToURL'] = DataTypeConversion::arrayToURL($arrayTwo);


echo '<table border=1>';

foreach ($diff as $newclass)
{
	foreach (get_class_methods($newclass) as $methods)
	{
		?>
		
		<tr>
			<th><?php echo $newclass; ?></th>
			<th><?php echo $methods; ?></th>
			<td><pre><?php echo htmlentities( print_r ( isset($outputs[$newclass."::".$methods]) ? $outputs[$newclass."::".$methods] : '' , true) ); ?></pre></td>
		</tr>
		
		<?php
		

	}
}

echo '</table>';