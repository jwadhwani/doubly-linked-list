<?php
/**
 * JDoublyLinkedList
 *
 * This class implements the doubly linked list using a hash table.
 * Using hash table prevents all possible recursive references and
 * allows for more efficient garbage collection. A sore point in PHP.
 *
 * I have heavily relied on the following 2 references for their algorithms.
 * Beginning Algorithms by Simon Harris and James Ross. Wrox publishing.
 * Data Structures and Algorithms in Java Fourth Edition by Michael T. Goodrich
 * and Roberto Tamassia. John Wiley & Sons.
 *
 * Please see blog post here: http://phptouch.com/2011/03/15/doubly-linked-list-in-php/
 *
 * *********************LICENSE****************************************
 * The MIT License (MIT)
 * Copyright (c) 2011 Jayesh Wadhwani
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the Software without restriction, including without
 * limitation the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in all copies or
 * substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
 * PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE
 * FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 * *********************LICENSE****************************************
 * @package JDoublyLinkedList
 * @author Jayesh Wadhwani
 * @copyright 2011 Jayesh Wadhwani
 * @license  The MIT License (MIT)
 * @version 1.0
 */
class JDoublyLinkedList
{
   /**
    * @var UID for the header node
    */
   private $_head;
   /**
    * @var UID for the trailer node
    */
   private $_tail;
   /**
    * @var size of list
    */
   private $_size;
   /**
    * @var hash table to store node objects
    */
   private $_list = array();

   /**
    * JDoublyLinkedList::__construct()
    *
    * @return
    */
   public function __construct(){
      //preapre header and trailer node objects
      $this->_head = $this->createNode('HEAD');
      $this->_tail = $this->createNode('TAIL');

      //the size of the list excludes the head and tail
      $this->_size = 0;

      $head = $this->getNode($this->_head);
      $head->setNext($this->_tail);

      $tail = $this->getNode($this->_tail);
      $tail->setPrevious($this->_head);
   }

   /**
    * JDoublyLinkedList::createNode()
    *
    * @param mixed $value
    * @return
    */
   public function createNode($value){
      if(!isset($value)) {
         throw new Exception('A value is required to create a node');
      }

      $node = new JNode($value);
      $uid = $node->getUid();
      $this->_list[$uid] = $node;
      return $uid;
   }

   /**
    * JDoublyLinkedList::getNode()
    * Given a UID get the node object
    *
    * @param mixed $uid
    * @return
    */
   public function getNode($uid){
      if(empty($uid)) {
         throw new Exception('A unique id is required.');
      }
      $ret = false;

      if(array_key_exists($uid, $this->_list) === true) {
         $ret = $this->_list[$uid];
      }
      return $ret;
   }

   /**
    * JDoublyLinkedList::deleteNode()
    *
    * Given a UID delete the node
    *
    * @param mixed $uid
    * @return
    */
   public function deleteNode($uid)
   {
      if(empty($uid)) {
         throw new Exception('A unique id is required.');
      }
      $ret = false;

      if(array_key_exists($uid, $this->_list) === true) {
         //get node vitals and destroy it
         $node = $this->getNode($uid);
         $nextUid = $node->getNext();
         $previousUid = $node->getPrevious();
         unset($node);

         //interchange next/prev adn delete it from the list
         $this->setNext($previousUid, $nextUid);
         $this->setPrevious($nextUid, $previousUid);
         unset($this->_list[$uid]);

         $ret = true;
      }
      return $ret;
   }

   /**
    * JDoublyLinkedList::setNext()
    *
    * This is a helper function. Given a UID for a node
    * set it as the next UID for the node.
    *
    * @param mixed $uid
    * @param mixed $nextUid
    * @return
    */
   public function setNext($uid, $nextUid)
   {
      if(empty($uid) || empty($nextUid)) {
         throw new Exception('Both a from and a to UIDs are required.');
      }
      //get the node object based on UID and set the next UID
      $node = $this->getNode($uid);

      if($node !== false) {
         $node->setNext($nextUid);
      }
   }

   /**
    * JDoublyLinkedList::getNext()
    *
    * This is a helper function to get the UID of the next node in list
    *
    * @param mixed $uid
    * @return
    */
   public function getNext($uid)
   {
      if(empty($uid)) {
         throw new Exception('A unique ID is required.');
      }

      $node = $this->getNode($uid);

      if($node !== false) {
         return $node->getNext();
      }
   }

   /**
    * JDoublyLinkedList::setPrevious()
    *
    * This is a helper function to set the previous
    * uid.
    *
    * @param mixed $uid - UID of the object to be processed on
    * @param mixed $prevUid - put this as next in the above object
    * @return
    */
   public function setPrevious($uid, $prevUid)
   {
      if(empty($uid) || empty($prevUid)) {
         throw new Exception('Both a from and a to UIDs are required.');
      }
      $node = $this->getNode($uid);

      if($node !== false) {
         $node->setPrevious($prevUid);
      }
   }

   /**
    * JDoublyLinkedList::getPrevious()
    *
    * This is a helper object to get the
    * uid of the previous node in the list
    *
    * @param mixed $uid
    * @return
    */
   public function getPrevious($uid)
   {
      if(empty($uid)) {
         throw new Exception('A unique ID is required.');
      }
      $node = $this->getNode($uid);

      if($node !== false) {
         return $node->getPrevious();
      }
   }

   /**
    * JDoublyLinkedList::getList()
    *
    * Retreives the hash table
    *
    * @return
    */
   public function getList()
   {
      return $this->_list;
   }

   /**
    * JDoublyLinkedList::addFirst()
    *
    * Add the node right after the head node
    *
    * @param mixed $uid
    * @return
    */
   public function addFirst($uid)
   {
      if(empty($uid)) {
         throw new Exception('A unique ID is required.');
      }
      $this->addNext($this->_head, $uid);
   }

   /**
    * JDoublyLinkedList::addNext()
    *
    * Adds the node right after the 'after' node.
    * Please node that after and uid are just uids.
    * In the function they are used to get the node objects
    * and worked on.
    *
    * @param mixed $after
    * @param mixed $uid
    * @return
    */
   public function addNext($after, $uid)
   {
      if(empty($uid) || empty($after)) {
         throw new Exception('Both a from and a to UIDs are required.');
      }
      $n = $this->getNext($after);
      $this->setNext($after, $uid);
      $this->setPrevious($uid, $after);
      $this->setNext($uid, $n);
      $this->_size++;
   }

   /**
    * JDoublyLinkedList::__destruct()
    *
    * Destroys each object in list.
    *
    * @return
    */
   public function __destruct()
   {
      foreach($this->_list as $l) {
         unset($l);
      }
      unset($this->_list, $this->_head, $this->_tail);
   }
}


/**
 * JNode
 *
 * This is a simple class to construct a node
 * Please note that each node object will be
 * eventually stored in a hash table where the
 * hash will be a UID.
 *
 * @package JDoublyLinkedList
 * @author Jayesh Wadhwani
 * @copyright 2011 Jayesh Wadhwani
 * @version 1.0
 * @access public
 */
class JNode
{
   /**
    * @var _value for the value field
   */
   private $_value;
   /**
    * @var _previous UID for the previous object in the link
   */
   private $_previous;
   /**
    * @var _next UID for the next object in the link
   */
   private $_next;
   /**
    * @var _uid UID for the current object
   */
   private $_uid;

   /**
    * JNode::__construct()
    *
    * @param mixed $value
    * @return
    */
   public function __construct($value)
   {
      if(!isset($value)) {
         throw new Exception('A value is required to create a node');
      }

      $this->setValue($value);
      $this->setUid();
   }

   /**
    * JNode::setUid()
    *
    * @param mixed $uid
    * @return
    */
   public function setUid($uid = null)
   {
      //if uid not supplied...generate
      if(empty($uid)) {
         $this->_uid = uniqid();
      } else {
         $this->_uid = $uid;
      }
   }

   /**
    * JNode::getUid()
    *
    * @return
    */
   public function getUid()
   {
      return $this->_uid;
   }

   /**
    * JNode::setValue()
    *
    * @param mixed $value
    * @return
    */
   public function setValue($value)
   {
      if(empty($value)) {
         throw new Exception('A value is required.');
      }
      $this->_value = $value;
   }

   /**
    * JNode::getValue()
    *
    * @return
    */
   public function getValue()
   {
      return $this->_value;
   }

   /**
    * JNode::getPrevious()
    *
    * @return
    */
   public function getPrevious()
   {
      return $this->_previous;
   }

   /**
    * JNode::setPrevious()
    *
    * @param mixed $previous
    * @return
    */
   public function setPrevious($previous)
   {
      if(empty($previous)) {
         throw new Exception('A unique ID is required.');
      }
      $this->_previous = $previous;
   }

   /**
    * JNode::getNext()
    *
    * @return
    */
   public function getNext()
   {
      return $this->_next;
   }

   /**
    * JNode::setNext()
    *
    * @param mixed $next
    * @return
    */
   public function setNext($next)
   {
      if(empty($next)) {
         throw new Exception('A unique ID is required.');
      }
      $this->_next = $next;
   }

   /**
    * JNode::__destruct()
    *
    * @return
    */
   public function __destruct()
   {
      unset($this->_next, $this->_previous, $this->_value, $this->_uid);
   }
}

/**
 * JLinkedListIterator
 *
 * This implements the iterator interface to provide iteration
 * for a doubly linked list.
 * Please note that the first two elements are the head and tail
 *
 * @package  JDoublyLinkedList
 * @author Jayesh Wadhwani
 * @copyright Jayesh Wadhwani
 * @license MIT
 * @version 1.0
 * @access public
 */
class JLinkedListIterator implements Iterator
{
   private $_position = 0;
   private $_list;
   private $_key;
   private $_ll;

   /**
    * JLinkedListIterator::__construct()
    *
    * @param mixed $ll
    * @return
    */
   public function __construct(JDoublyLinkedList $ll)
   {
      $this->_list = $ll->getList();
      $this->_ll = $ll;
      $this->rewind();
   }

   /**
    * JLinkedListIterator::rewind()
    *
    * @return
    */
   public function rewind()
   {
      reset($this->_list);
      next($this->_list); //skip the head
      next($this->_list); //skip the tail
      $this->_key = key($this->_list);
      $this->_position = 1;
   }

   /**
    * JLinkedListIterator::current()
    *
    * @return
    */
   public function current()
   {
      return $this->_ll->getNode($this->_key);
   }

   /**
    * JLinkedListIterator::key()
    *
    * @return
    */
   public function key()
   {
      return $this->_key;
   }

   /**
    * JLinkedListIterator::next()
    *
    * @return
    */
   public function next()
   {
      next($this->_list);
      $this->_key = key($this->_list);
   }

   /**
    * JLinkedListIterator::valid()
    *
    * @return
    */
   function valid()
   {
      $firstPosition = 0;
      $lastPosition = count($this->_list) - 2; //ignore head and tail

      return isset($this->_list[$this->_key]) && $this->_position > $firstPosition &&
         $this->_position < $lastPosition;
   }
  /**
    * JLinkedListIterator::__destruct()
    *
    * @return
    */
   public function __destruct()
   {
      unset($this->_list, $this->_ll);
   }
}

//Please note that this is a minimum implementation. You could add many more methods such as inserting at an index, getting the size of the list, indexOf.
//My main intent is to show how to use UIDs as object references. Also note that in the main class I have helper classes to create, add and delete nodes.
//In the following example I have included memory use statements to make sure that I clean up properly.


