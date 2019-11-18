<template>
  <v-container fluid>
    <br />

    <!-- Project Info -->
    <div>
      <h4>Project info:</h4>
      <p>
        The main purpose of the "Virtual Wallet" platform is to provide a Web Application that replaces
        a physical wallet, by keeping virtual money and handling small transaction between platform
        users or external entities.
      </p>
      <p>
        The main concept of the platform is the virtual wallet, that behaves like
        a financial account, with a balance (amount of money currently on the wallet – it must be positive
        or zero) and multiple financial movements (or transactions), each representing either an income
        (a credit) or an expense (a debit).
      </p>
      <p>
        Income movements are created by payments from the users to
        the platform or through transfers from other virtual wallets.
      </p>
      <p>
        Expense movements correspond to
        payments to external entities or transfers to other virtual wallets.
      </p>
      <p>
         Each movement (either income
    or expense) is limited to a maximum of 5,000.00 € and increases or decreases the wallet balance
    – an income (credit) increases the balance and an expense (debit) decreases the balance.
      </p>
      <p>
        When a user transfer money from his virtual wallet to another virtual wallet, the platform creates two
        financial movements: an expense (debit – balance decreases) on the user wallet and an income
        (credit – balance increases) on the other virtual wallet.
      </p>
    </div>

    <br />

    <!-- Developers -->
    <h4>Developed by:</h4>
    <v-row>
      <v-col v-for="card in cards" :key="card.title" :cols="card.flex">
        <v-card>
          <v-img
            :src="card.src"
            class="white--text align-end mx-auto"
            gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
            height="400px"
          >
            <v-card-title v-text="card.title"></v-card-title>
          </v-img>

          <v-card-actions>
            <v-spacer></v-spacer>

            <v-btn icon :href="card.facebook">
              <v-icon>mdi-facebook-box</v-icon>
            </v-btn>

            <v-btn icon :href="card.linkedIn">
              <v-icon>mdi-linkedin-box</v-icon>
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  data: () => ({
    cards: [
    {
        title: "Rodrigo Santos",
        src:
          "https://z-m-scontent.flis5-1.fna.fbcdn.net/v/t1.0-9/12038209_932643853475988_6450784216033743893_n.jpg?_nc_cat=103&_nc_oc=AQlP4yS7cYCvjzOwA0Dbukl34QH3-O7-_qy_FY29tFg5QeB00xO8aUrALqGkQjHnlFo&_nc_ad=z-m&_nc_cid=1272&_nc_zor=9&_nc_ht=z-m-scontent.flis5-1.fna&oh=84260068536b1cc0191c26a52ff98b62&oe=5E530CA1",
        flex: 4,
        facebook: "https://www.facebook.com/rodrigo.h.f.santos",
        linkedIn: "https://www.linkedin.com/in/rodrigohfsantos/"
      },
      {
        title: "Daniel Rodrigues",
        src:
          "https://z-m-scontent.flis5-1.fna.fbcdn.net/v/t1.0-9/11350580_856837901090657_2103519197645713210_n.jpg?_nc_cat=111&_nc_oc=AQnlQwUens0Zdr0cuHZ8bvbYLpGuqGQc7URgkqbGDyTYugj2qGa-1ex8BSfJVx-pLfc&_nc_ad=z-m&_nc_cid=1272&_nc_zor=9&_nc_ht=z-m-scontent.flis5-1.fna&oh=8124777afef24bf593c53bfc86a550ca&oe=5E62875D",
        flex: 4,
        facebook: "https://www.facebook.com/profile.php?id=100002933664528",
        linkedIn: ""
      },
      {
        title: "Luís Aparício",
        src:
            "https://scontent.flis7-1.fna.fbcdn.net/v/t1.0-9/10881611_1031848416840682_1753648519418611895_n.jpg?_nc_cat=106&_nc_oc=AQlxHX-FfXNiaNl_OJ1HNOrG7E21B9LKG4yBFtinjcD_j8fLvQG657I_bNvEzovNWzk&_nc_ht=scontent.flis7-1.fna&oh=b60e18782850106319c1c3bc01cc376f&oe=5E55D315",
        flex: 4,
        facebook: "https://www.facebook.com/luis.aparicio.900",
        linkedIn: "https://www.linkedin.com/in/luís-aparício"
      }
    ]
  })
};
</script>