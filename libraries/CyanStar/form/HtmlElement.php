<?php


class HtmlElement
{

	static function SingleSelect($name, $vals, $default = null, $useOnlyValues = false)
	{
		$t = '<select name="'.$name.'">'.self::SelectOptions($vals, $default, $useOnlyValues).'</select>';
	
		return $t;
	}



	static function SelectOptions($vals, $default = null, $useOnlyValues = false, $paddingString = "&nbsp; &nbsp; &nbsp;", $_level = 0)
	{
		$s = array();
	
		foreach ($vals as $label => $val)
		{
			$label = htmlentities(html_entity_decode($label));
			$padding = str_repeat($paddingString, $_level);
			
			if (is_array($val))
			{
				$s[] = "<optgroup label=\"".$padding.$label."\">";
				$s[] = self::SelectOptions($val, $default, $useOnlyValues, $paddingString, $_level + 1);
				$s[] = "</optgroup>";
			}
			else
			{
				$val = htmlentities(html_entity_decode($val));
				
				if ($useOnlyValues)
					$label = $val;
				
				$r = "<option value=\"$val\">$label</option>";

				if ( (is_array($default) && in_array($val, $default)) || ($val == $default) )
				{
					$r = str_replace("<option ", "<option selected=\"selected\" ", $r);
				}
				
				$s[] = $r;
			}
		
		}
	
		return implode("\n", $s);
	}
	
	
	
	
	
	static function input($type, $name, $value = null)
	{
		if ($type == 'textarea')
			return '<textarea name="'.$name.'">'.$value.'</textarea>';
		else if ($type == 'readonly')
			return '<input type="text" readonly="readonly" name="'.$name.'" value="'.$value.'" />';
		else
			return '<input type="'.$type.'" name="'.$name.'" value="'.$value.'" />';
	}
	



	static function prependToTag($fulltext, $element, $newText)
	{
		return str_replace("<$element>", "<$element>".$newText, $fulltext);
	}
	
	static function appendToTag($fulltext, $element, $newText)
	{
		return str_replace("</$element>", $newText."</$element>", $fulltext);
	}
	
	
	static function getDomain($link)
	{
		$minusProtocol = LibStrings::getPiece($link, "//", 1);
		return LibStrings::getPiece($minusProtocol, "/", 0);
	}
	
	
	
	static function linkify($href, $text = null, $attribute = null)
	{
		if (empty($text))
			$text = $href;
			
			
		return '<a href="'.$href.'" '.$attribute.'>'.$text.'</a>';
	}
	
	
	
	static function singleDimensionTable($data)
	{
		$render = array();
		$render[] = '<table>';
		
		foreach ($data as $key => $val)
		{
			
			$renderedRow = '
			<tr>
				<th>'.$key.'</th>
				<td>'.$val.'</td>
			</tr>';
					
			$render[] = $renderedRow;

		}
		
		$render[] = '</table>';

		return implode("\n", $render);
	}
	
	
	
	
	
	static function twoDimensionalTable($data)
	{
		$return = "<table>";		
		
		foreach ($data as $count => $row)
		{
			if ($count > 0)
				break;
		
			$return .= "<tr>";
					
			foreach ($row as $column => $val)
			{
				$return .= "<th>".$column."</th>";			
			}
		
			$return .= "</tr>";
		}

		foreach ($data as $count => $row)
		{
			$return .= "<tr>";
		
			foreach ($row as $column => $val)
			{
				$return .= "<td>".$val."</td>";			
			}
		
			$return .= "</tr>";
		}

	
		$return .= "</table>";
	
		return $return;
	
	}	
}
