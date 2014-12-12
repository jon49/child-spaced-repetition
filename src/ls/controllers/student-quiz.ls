require! {
  m: mithril
  r: ramda
  Student: './../models/student'
}

class Controller

  ->
    @studentId = m.route.param 'id'
    Student.store.cards = Student.cards @studentId

  # record data before window closes
  onunload: (e) ->
    Student.sendStudentResults!

  # iterate to next card, if finished, then submit to server
  nextCard: ->
    {cards, hints, studentName} = Student.store.cards!
    card = cards.shift!
    hint = (r.find r.propEq \deckId card.deckId) hints
    content =
      title: studentName
      hint: if hint then hint.hint else ''
      card: card.content

# Controller.nextCard =
module.exports = Controller
