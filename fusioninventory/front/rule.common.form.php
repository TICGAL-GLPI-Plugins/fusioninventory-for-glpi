<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ------------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory project.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    Walid Nouh
   @co-author 
   @copyright Copyright (c) 2010-2011 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}
Session::checkLoginUser();
$rule = $rulecollection->getRuleClass();
$rulecollection->checkGlobal('r');

if (!isset($_GET["id"])) {
   $_GET["id"] = "";
}
$rulecriteria = new RuleCriteria(get_class($rule));
$ruleaction = new RuleAction(get_class($rule));

if (isset($_POST["delete_criteria"])) {
   $rulecollection->checkGlobal('w');

   if (count($_POST["item"])) {
      foreach ($_POST["item"] as $key => $val) {
         $input["id"] = $key;
         $rulecriteria->delete($input);
      }
   }
   // Can't do this in RuleCriteria, so do it here
   $rule->update(array('id'       => $_POST['rules_id'],
                       'date_mod' => $_SESSION['glpi_currenttime']));
   Html::back();

} else if (isset($_POST["delete_action"])) {
   $rulecollection->checkGlobal('w');

   if (count($_POST["item"])) {
      foreach ($_POST["item"] as $key => $val) {
         $input["id"] = $key;
         $ruleaction->delete($input);
      }
   }
   // Can't do this in RuleAction, so do it here
   $rule->update(array('id'       => $_POST['rules_id'],
                       'date_mod' => $_SESSION['glpi_currenttime']));
   Html::back();

} else if (isset($_POST["add_criteria"])) {
   $rulecollection->checkGlobal('w');
   $rulecriteria->add($_POST);

   // Can't do this in RuleCriteria, so do it here
   $rule->update(array('id'       => $_POST['rules_id'],
                       'date_mod' => $_SESSION['glpi_currenttime']));
   Html::back();

} else if (isset($_POST["add_action"])) {
   $rulecollection->checkGlobal('w');
   $ruleaction->add($_POST);

   // Can't do this in RuleCriteria, so do it here
   $rule->update(array('id'       => $_POST['rules_id'],
                       'date_mod' => $_SESSION['glpi_currenttime']));
   Html::back();

} else if (isset($_POST["update"])) {
   $rulecollection->checkGlobal('w');
   $rule->update($_POST);

   Event::log($_POST['id'], "rules", 4, "setup", $_SESSION["glpiname"]." ".$LANG['log'][21]);
   Html::back();

} else if (isset($_POST["add"])) {
   $rulecollection->checkGlobal('w');

   $newID = $rule->add($_POST);
   Event::log($newID, "rules", 4, "setup", $_SESSION["glpiname"]." ".$LANG['log'][20]);
   Html::redirect($_SERVER['HTTP_REFERER']."?id=$newID");

} else if (isset($_POST["delete"])) {
   $rulecollection->checkGlobal('w');
   $rulecollection->deleteRuleOrder($_POST["ranking"]);
   $rule->delete($_POST);

   Event::log($_POST["id"], "rules", 4, "setup", $_SESSION["glpiname"]." ".$LANG['log'][22]);
   Html::redirect(str_replace('.form','',$_SERVER['PHP_SELF']));
}

Html::header($LANG['common'][12], $_SERVER['PHP_SELF'], "admin",
             $rulecollection->menu_type, $rulecollection->menu_option);

$rule->showForm($_GET["id"]);
Html::footer();

?>