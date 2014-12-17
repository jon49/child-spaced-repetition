require! {m: mithril}

Student = (

  cards: (studentId) ->
    m.request {method: \GET, url: "/api/students/#{studentId}/cards"}

  sendStudentResult: (studentId, performance) ->
    m.request (
      method: \PUT
      url: "/api/students/#{studentId}/cards"
      data: {cards: performance}
    )

  decks: (studentId) ->
    m.request {method: \GET, url: "/api/students/#{studentId}/decks"}

  toggleDeck: (studentId, deckId) ->
    m.request (
      method: \PUT
      url: "/api/students/#{studentId}/decks"
      data: {deckId: deckId}
    )

)

module.exports = Student
