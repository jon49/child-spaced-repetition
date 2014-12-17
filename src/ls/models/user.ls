require! {m: mithril}

User = (

  students: ->
    m.request {method: 'GET', url: '/api/students'}

  changeStudentName: (studentId, newName) ->
    m.request (
      method: \PUT
      url: "/api/students/#{studentId}"
      data: {studentName: newName}
    )

  deleteStudent: (studentId) ->
    m.request (
      method: \DELETE
      url: "/api/students/#{studentId}"
    )

  createStudent: (studentName) ->
    m.request (
      method: \POST
      url: \/api/students
      data: {studentName: studentName}
    )

  decks: ->
    m.request {method: \GET, url: \/api/decks}

  changeDeckName: (deckId, newName) ->
    m.request (
      method: \PUT
      url: "/api/decks/#{deckId}"
      data: {deckName: newName}
    )

  deleteDeck: (deckId) ->
    m.request (
      method: \DELETE
      url: "/api/decks/#{deckId}"
    )

  createDeck: (deckName) ->
    m.request (
      method: \POST
      url: \/api/decks
      data: {deckName: deckName}
    )
)


module.exports = User
