require! {
  m: mithril
  r: ramda
  Student: \../models/student
}

class Controller

  ->
    self = @
    @studentId = m.route.param \id
    @decks = []
    Student.decks @studentId .then (data) !->
      self.decks = (data || []).map ->
        decks = {}
        decks.deckId = parseInt it.deckId
        decks.active = Boolean parseInt it.active
        decks.deckName = String it.deckName
        decks

  # # record data before window closes
  # onunload: (e) ->
  #   Student.sendStudentResult @performance

  toggleThisDeck: (deckIndex) !->
    @decks[deckIndex].active = not @decks[deckIndex].active

  toggleDeck: (deckId) ->
    self = @
    deckIndex = (r.findIndex r.propEq \deckId deckId) self.decks
    self.toggleThisDeck deckIndex
    Student.toggleDeck self.studentId, deckId
      .then !->, self.toggleThisDeck.bind self, deckIndex

module.exports = Controller
