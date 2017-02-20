<?php

interface IMethods
{
   public function indexTab($var);
   public function nameTab($var);
   public function getNumber();
   public function printTab();
   
} 

abstract class SuitFace implements IMethods 
{
   public static $arr = array();
   
   public function __construct($tab)
   {
      $this-> arr = $tab;
      
   }

   private function getTab() 
   {
      return $this->arr;
   }
 
   public function indexTab($index)
   {
      $tab = self::getTab();
      if(!empty($tab))
      {
        return $tab[$index];
      }
   }

   public function nameTab($name)
   {
      $tab = self::getTab();
      return array_search($name);
   }

   public function getNumber()
   {
      $w = self::getTab();
      return count($w);
   }

   public function printTab()
   {
     $tab = self::getTab();
     if(!empty($tab))
     {
		 for($i = 0; $i < count($tab); $i++)
		 {
		    echo $tab[$i]."<br />";
		 }
     }
   }
   
}

class SuitAndFace extends SuitFace // SuitAndFace
{
   public function __construct($collection)
   {
      parent::__construct($collection); 
   }
}

class Card
{
    private static $color;
    private static $value;
    

    public function __construct()
    {
      $this->color = self::setColor();
      $this->value = self::setValue();
    }

    public static function setColor()
    {
      $col = new SuitAndFace(["&#9827", "&#9824", "<span style='color:red'>&#9830</span>", "<span style='color:red'>&#9829</span>"]);
      $los = rand(0, 3);
      self::$color = $col->indexTab($los);
      return self::$color;
      
    }

    public static function setValue()
    {
       $val = new SuitAndFace([2, 3, 4, 5, 6, 7, 8, 9, 10, "Jack", "Queen", "King", "Ace"]);
       $los = rand(0, 12);
       self::$value = $val->indexTab($los);
       return self::$value;
     
    }

    public static function getColor()
    {
       return self::$color;
    }

    public static function getValue()
    {  
       return self::$value;
    } 

    public function displayCard()
    {
       return $this->getValue()." ".$this->getColor();
    } 
  
}

class Deck
{
    private static $cards = array();

    public function __construct()
    {
      // 52 cards
      // SINGLETON, only one deck at a game
      if(!self::$cards)
      {
		  $col = new SuitAndFace(["&#9827", "&#9824", "<span style='color:red'>&#9830</span>", "<span style='color:red'>&#9829</span>"]);
		  $val = new SuitAndFace([2, 3, 4, 5, 6, 7, 8, 9, 10, "Jack", "Queen", "King", "Ace"]);
		  for($i = 0; $i < $col->getNumber(); $i++)
		  {
		      for($j = 0; $j < $val->getNumber(); $j++)
		      {
		          self::$cards[]  = $val->indexTab($j)." ".$col->indexTab($i);
		      }
		  }
          
      }
      else
      {
          return self::$cards;
      }
    }
    
    public static function getCards()
    {
       return self::$cards;
    }

    public function shuffle()
    {
       $a = self::getCards();
       $q = count($a);
       $tab = array();
       
       for($j = 0; $j < $q; $j++)
       {
          $l = array_rand($a);
          $tab[$j] = $a[$l];
          unset($a[$l]);
          
       }
 
       return self::$cards = $tab;
    }

    public function removeCardsFromDeck($q)
    {
       $b = self::getCards();
       for($i = 0; $i < $q; $i++)
       {
          array_shift($b);  
       }
       self::$cards = $b; 
    }

    
    public function showAllCards($deck)
    {
       for($i = 0; $i < count($deck); $i++)
       {
          echo "< ".$deck[$i]."> ";
          if($i == 12 || $i == 25 || $i == 38)
          {
            echo "<br />";
          }
       }

       echo "<br /><br />";
      
    }
}

class Hand
{
   private $cardsOnHand = array();

   public function __construct(Deck $set, $quantity)
   {
          $current = $set::getCards(); 
          $tab = array();
		  for($i = 0; $i < $quantity; $i++)
		  {
		     $tab[] = $current[$i];
		  }
		  
		  $set->removeCardsFromDeck($quantity);
          $this->cardsOnHand = $tab;
      
   }
   
   public function getCards()
   {
      return $this->cardsOnHand;
   }
    
}


class Player
{
   private $name;
   private $hand = array();
   public $points = 0;
   
   
   public function __construct($name, $cards)
   {
       $this->name = $name;
       $this->hand = $cards;
   }

   public function setPoints()
   {
      $this->points += 2;
   }

   public function getPoints()
   {
      $score = $this->points;
      return $score; 
   } 
   
   public function getName()
   {
       return $this->name;
   }

   private function getHand()
   {  
      return $this->hand;
   }

   public function howManyCards()
   {
      return count($this->getHand());
   }

   public function showHand()
   {
     $arr = $this->getHand();
     for($i = 0; $i < count($arr); $i++)
     {
        echo "< ".$arr[$i]."> ";
     }
     echo "<br /><br />";
   }

   public function putOneCard()
   {
     $w = $this->getHand();
     $firstcard = $w[0];
     array_shift($w);
     $this->hand = $w;
     
     return "< ".$firstcard."> ";
   }

   
}

class WarGame
{
   private $deck;
   private $playerOne;
   private $playerTwo;

   public function __construct(Deck $deck, Player $player1, Player $player2)
   { 
       $this->deck = $deck;
       $this->playerOne = $player1;
       $this->playerTwo = $player2;
    
   }

   public function getDeck()
   {
       return $this->deck;
   }

   public function getPlayerOne()
   {
       return $this->playerOne;
   }

   public function getPlayerTwo()
   {
       return $this->playerTwo;
   }

   public function getStronger($l, $n)
   {
      $val1 = $this->valueOfCard($l);
      $val2 = $this->valueOfCard($n);
      if($val1 > $val2)
      {
         return $l;
      }
      else if($val1 < $val2)
      {
         return $n;
      } 
      else if($val1 == $val2)
      {
        return "REMIS";
      } 
   }

   private function valueOfCard($m)
   {
      $val = (int)$m[2];
      if($val == 1)
      {
            $val = 10;
      }
      if($val == 0)
      {
         if($m[2] == 'J')
         {
            $val = 11;
         }
         else if($m[2] == 'Q')
         {
            $val = 12;
         }
         else if($m[2] == 'K')
         {
            $val = 13;
         } 
         else if($m[2] == 'A')
         {
            $val = 14;
         }
      }
        return $val;
   } 

   
   public function Battle()
   {
          
          echo $this->getPlayerOne()->getName()."<br />";
          echo "Set of cards:<br />";
          $this->getPlayerOne()->showHand();
          echo "<br />";
          echo " CONTRA <br /><br />";
          echo $this->getPlayerTwo()->getName()."<br />";
          echo "Set of cards: <br />";
          $this->getPlayerTwo()->showHand();
          echo "<br /><br />";
          echo "Start the battle... <br /><br />"; 
           
		  while(($this->getPlayerOne()->howManyCards() != 0) && ($this->getPlayerTwo()->howManyCards() != 0))
		  {
		     $firstcard = $this->getPlayerOne()->putOneCard();
             echo $firstcard;
		     echo " against "; 
		     $secondcard = $this->getPlayerTwo()->putOneCard();
             echo $secondcard;
		     echo "<br />";
             $result = $this->getStronger($firstcard, $secondcard);
             echo "Stronger card: ".$result."<br>";
         
             if($result == $firstcard)
             {
                $this->getPlayerOne()->setPoints();
                echo "Player: <u>".$this->getPlayerOne()->getName()."</u> won!<br />";
             }
             else if($result == $secondcard)
             {
                $this->getPlayerTwo()->setPoints();
                echo "Player: <u>".$this->getPlayerTwo()->getName()."</u> won!<br />";
             }
             else if($result == "REMIS")
             {
                echo "Players: <u>".$this->getPlayerOne()->getName()." and ".$this->getPlayerTwo()->getName()."</u> won!<br />";
             }
              
             echo "<br /><br />";
            
            
		    
		  }
          
          echo "<br /><b> GAME OVER! </b><br />";
          $points1 = $this->getPlayerOne()->getPoints();
          $points2 = $this->getPlayerTwo()->getPoints();
          if($points1 > $points2)
          {
             $winner = $this->getPlayerOne()->getName();
          }
          else if($points1 < $points2)
          {
             $winner = $this->getPlayerTwo()->getName();
          }
          else if($points1 == $points2)
          {
             $winner = $this->getPlayerOne()->getName()." AND ".$this->getPlayerTwo()->getName();
          } 
          echo "Player ".$this->getPlayerOne()->getName()." gained ".$points1." points<br />";
          echo "Player ".$this->getPlayerTwo()->getName()." gained ".$points2." points<br />";

          
          echo "<br /> The winner is....".$winner." !!! <br />";

          
      
   }
  
}



//------------------------------------tests--------------------------------------------------------------

echo "<h1>WAR (card game) </h1><br /><br />";
//-------------------------------------------------------------
echo "<div style='font-size: 35px'>";
echo "<b>FIRST GAME: </b><br />";
$talia = new Deck();
echo "Deck of 52 cards: <br />";
$talia->showAllCards(Deck::getCards());
echo "Shuffling...: <br />";
$talia->shuffle();
echo "After shuffling...<br />";
$talia->showAllCards(Deck::getCards());
$reka1 = new Hand($talia, 26);
$reka2 = new Hand($talia, 26);
$player1 = new Player("Anna Taylor", $reka1->getCards());
$player2 = new Player("Kurt Mason", $reka2->getCards());

$game = new WarGame($talia, $player1, $player2);
$game->Battle();
//--------------------------------------------------------------
echo "<br /><br /><b>SECOND GAME: </b><br />";
$talia = new Deck();
echo "Deck of 52 cards: <br />";
$talia->showAllCards(Deck::getCards());
echo "Shuffling...: <br />";
$talia->shuffle();
echo "After shuffling...<br />";
$talia->showAllCards(Deck::getCards());
$reka1 = new Hand($talia, 26);
$reka2 = new Hand($talia, 26);
$player1 = new Player("Joan Doe", $reka1->getCards());
$player2 = new Player("Jack The Ripper", $reka2->getCards());

$game = new WarGame($talia, $player1, $player2);
$game->Battle();
echo "</div>";

?>
