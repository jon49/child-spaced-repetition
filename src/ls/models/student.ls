require! {m: mithril}

Student = {}

Student.cards = (studentId) ->
  m.request {method: \GET, url: "/api/students/#{studentId}/cards"}

Student.sendStudentResult = (studentId, performance) ->
  m.request (
    method: \PUT
    url: "/api/students/#{studentId}/cards"
    data: {cards: performance}
  )

Student.decks = (studentId) ->
  m.request {method: \GET, url: "/api/students/#{studentId}/decks"}

Student.toggleDeck = (studentId, deckId) ->
  m.request (
    method: \PUT
    url: "/api/students/#{studentId}/decks"
    data: {deckId: deckId}
  )

module.exports = Student
