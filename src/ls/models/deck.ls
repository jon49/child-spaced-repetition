require! {m: mithril}

Deck = (

  cards: (deckId) ->
    m.request {method: \GET, url: "/api/decks/#{deckId}/cards"}

  changeCardName: (deckId, cardId, newName) ->
    m.request (
      method: \PUT
      url: "/api/decks/#{deckId}/cards"
      data: {content: newName, cardId: cardId}
    )

  deleteCard: (deckId, cardId) ->
    m.request (
      method: \DELETE
      url: "/api/decks/#{deckId}/cards"
    )

  createCard: (deckId, content) ->
    m.request (
      method: \POST
      url: "/api/decks/#{deckId}/cards"
      data: {content: content}
    )
)

module.exports = Deck
