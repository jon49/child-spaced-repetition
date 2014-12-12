require! {
  Student: \../models/student
  r: ramda
}

updateCard = (ctrl, cardSessionData) ->
  {start, cardId, deckId} = cardSessionData
  Student.store.cards =
    lapsedTime: Date.now! - start
    cardId: cardId
    deckId: deckId
  ctrl.nextCard!

module.exports = 
  updateCard: r.curryN 3 updateCard
