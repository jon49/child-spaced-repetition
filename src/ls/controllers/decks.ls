require! {
  m: mithril
  r: ramda
  User: './../models/user'
}

class Controller

  ->
    self = @
    User.decks! .then (data) !->
      self.decks = data

  deckIndex: (deckId) ->
    @decks
    |> r.findIndex r.propEq \deckId deckId

  editName: (deckId) ->
    editIndex = @deckIndex deckId
    @decks[editIndex].edit = true

  newName: (deckId) !->
    deckIndex = @deckIndex deckId
    $edit = document.getElementById \edit
    newName = if ($edit) then $edit.value else ''
    switch !!newName
    | true =>
      User.changeDeckName deckId, newName
      @decks[deckIndex].edit = false
      @decks[deckIndex].deckName = newName
    | _ =>
      User.deleteDeck deckId
      @decks.splice deckIndex, 1

  editNewDeck: ->
    @newDeck = true

  createDeck: ->
    self = @
    @newDeck = false
    $edit = document.getElementById \edit
    deckName = if $edit then $edit.value else ''
    switch !!deckName
    | true =>
      User.createDeck deckName .then (data) !->
        self.decks.push data
    | _ => # do nothing

module.exports = Controller
