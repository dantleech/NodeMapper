Feature: Object manager find
    In order to locate Sulu Objects in the content repository
    As a developer
    I need to be able to do that

    Background:
        Given there exists an object at path "/tests/foo" of type "page"
        And the following configuration is used:
        """
        discriminator: object_class
        mapping:
            page: 
                class: SuluMapper\Tests\Page
        """

    Scenario: Find an object by path
        Given I call "find" with "['/path/foo']"
        Then it should return an object of type "SuluMapper\Tests\Page"

    Scenario: Find with non-existing path
        Given I call "find" with "['/foobar/barfoo']"
        Then an exception of type "SuluMapper\Exception\ObjectNotFound" should be thrown
