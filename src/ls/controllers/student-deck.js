// Generated by LiveScript 1.2.0
var m, r, Student, Controller;
m = require('mithril');
r = require('ramda');
Student = require('../models/student');
Controller = (function(){
  Controller.displayName = 'Controller';
  var prototype = Controller.prototype, constructor = Controller;
  function Controller(){
    var self;
    self = this;
    this.studentId = m.route.param('id');
    this.decks = [];
    Student.decks(this.studentId).then(function(data){
      self.decks = (data || []).map(function(it){
        var decks;
        decks = {};
        decks.deckId = parseInt(it.deckId);
        decks.active = Boolean(parseInt(it.active));
        decks.deckName = String(it.deckName);
        return decks;
      });
    });
  }
  prototype.toggleThisDeck = function(deckIndex){
    this.decks[deckIndex].active = !this.decks[deckIndex].active;
  };
  prototype.toggleDeck = function(deckId){
    var self, deckIndex;
    self = this;
    deckIndex = r.findIndex(r.propEq('deckId', deckId))(self.decks);
    self.toggleThisDeck(deckIndex);
    return Student.toggleDeck(self.studentId, deckId).then(function(){}, self.toggleThisDeck.bind(self, deckIndex));
  };
  return Controller;
}());
module.exports = Controller;