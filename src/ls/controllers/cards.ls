require! {
  m: mithril
  r: ramda
  Deck: './../models/deck'
}

class Controller

  ->
    @deckId = m.route.param \id
    self = @
    Deck.cards @deckId .then (data) !->
      self.cards = data

  cardIndex: (cardId) ->
    @cards
    |> r.findIndex r.propEq \cardId cardId

  editName: (cardId) ->
    editIndex = @cardIndex cardId
    @cards[editIndex].edit = true

  newName: (cardId) !->
    cardIndex = @cardIndex cardId
    $edit = document.getElementById \edit
    newName = if ($edit) then $edit.value else ''
    switch !!newName
    | true =>
      Deck.changeCardName @deckId, cardId, newName
      @cards[cardIndex].edit = false
      @cards[cardIndex].content = newName
    | _ =>
      Deck.deleteCard @deckId, cardId
      @cards.splice cardIndex, 1

  editNewCard: ->
    @newCard = true

  createCard: ->
    self = @
    @newCard = false
    $edit = document.getElementById \edit
    content = if $edit then $edit.value else ''
    switch !!content
    | true =>
      Deck.createCard @deckId, content .then (data) !->
        self.cards.push data
    | _ => # do nothing

module.exports = Controller
